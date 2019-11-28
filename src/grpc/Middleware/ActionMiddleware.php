<?php
namespace Imi\Grpc\Middleware;

use Imi\RequestContext;
use Imi\Util\Http\Response;
use Imi\Bean\Annotation\Bean;
use Imi\Controller\HttpController;
use Imi\Grpc\Parser;
use Imi\Server\Http\Message\Request;
use Imi\Server\Annotation\ServerInject;
use Imi\Util\Stream\MemoryStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @Bean("GrpcActionMiddleware")
 */
class ActionMiddleware implements MiddlewareInterface
{
    /**
     * @ServerInject("View")
     *
     * @var \Imi\Server\View\View
     */
    protected $view;

    /**
     * 动作方法参数缓存
     *
     * @var \ReflectionParameter[]
     */
    private $actionMethodParams = [];

    /**
     * 处理方法
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 获取Response对象
        $response = $handler->handle($request);
        $context = RequestContext::getContext();
        $context['response'] = $response;
        /** @var \Imi\Server\Http\Route\RouteResult $result */
        $result = $context['routeResult'];
        if(null === $result)
        {
            throw new \RuntimeException('RequestContent not found routeResult');
        }
        // 路由匹配结果是否是[控制器对象, 方法名]
        $isObject = is_array($result->callable) && isset($result->callable[0]) && $result->callable[0] instanceof HttpController;
        $useObjectRequestAndResponse = $isObject && !$result->routeItem->singleton;
        if($useObjectRequestAndResponse)
        {
            // 复制一份控制器对象
            $result->callable[0] = clone $result->callable[0];
            // 传入Request和Response对象
            $result->callable[0]->request = $request;
            $result->callable[0]->response = $response;
        }
        // 执行动作
        $actionResult = ($result->callable)(...$this->prepareActionParams($request, $result));

        if($useObjectRequestAndResponse)
        {
            // 获得控制器中的Response
            $response = $result->callable[0]->response;
        }
        else
        {
            $response = $context['response'];
        }

        return $response->withBody(new MemoryStream(Parser::serializeMessage($actionResult)));
    }
    
    /**
     * 准备调用action的参数
     * @param Request $request
     * @param array $routeResult
     * @return array
     */
    private function prepareActionParams(Request $request, $routeResult)
    {
        // 根据动作回调类型获取反射
        if(is_array($routeResult->callable))
        {
            if(is_string($routeResult->callable[0]))
            {
                $class = $routeResult->callable[0];
            }
            else
            {
                $class = get_class($routeResult->callable[0]);
            }
            $method = $routeResult->callable[1];
            if(isset($this->actionMethodParams[$class][$method]))
            {
                $params = $this->actionMethodParams[$class][$method];
            }
            else
            {
                $ref = new \ReflectionMethod($routeResult->callable[0], $routeResult->callable[1]);
                $params = $this->actionMethodParams[$class][$method] = $ref->getParameters();
            }
        }
        else if(!$routeResult->callable instanceof \Closure)
        {
            $ref = new \ReflectionFunction($routeResult->callable);
            $params = $ref->getParameters();
        }
        else
        {
            return [];
        }
        $result = [];
        /** @var \ReflectionParameter[] $params */
        foreach($params as $param)
        {
            if($type = $param->getType())
            {
                $type = (string)$type;
                if(is_subclass_of($type, \Google\Protobuf\Internal\Message::class))
                {
                    $value = Parser::deserializeMessage([$type, null], (string)$request->getBody());
                    if(null === $value)
                    {
                        throw new \RuntimeException(sprintf('RequestData %s deserialize failed', $type));
                    }
                    $result[] = $value;
                }
                else
                {
                    return null;
                }
            }
            else if(isset($routeResult->params[$param->name]))
            {
                // 路由解析出来的参数
                $result[] = $routeResult->params[$param->name];
            }
            else if($request->hasPost($param->name))
            {
                // post
                $result[] = $request->post($param->name);
            }
            else if(null !== ($value = $request->get($param->name)))
            {
                // get
                $result[] = $value;
            }
            else
            {
                $parsedBody = $request->getParsedBody();
                if(is_object($parsedBody) && isset($parsedBody->{$param->name}))
                {
                    $result[] = $parsedBody->{$param->name};
                }
                else if(is_array($parsedBody) && isset($parsedBody[$param->name]))
                {
                    $result[] = $parsedBody[$param->name];
                }
                else if($param->isOptional())
                {
                    // 方法默认值
                    $result[] = $param->getDefaultValue();
                }
                else
                {
                    $result[] = null;
                }
            }
        }
        return $result;
    }
    
}