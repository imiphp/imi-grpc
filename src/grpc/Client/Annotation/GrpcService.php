<?php
namespace Imi\Grpc\Client\Annotation;

use Imi\Bean\Annotation\Parser;
use Imi\Rpc\Annotation\RpcService;
use Imi\Rpc\Client\Pool\RpcClientPool;

/**
 * gRPC 服务对象注入
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 * @Parser("Imi\Aop\Parser\AopParser")
 */
class GrpcService extends RpcService
{
    /**
     * 连接池名称
     *
     * @var string|null
     */
    public $poolName;

    /**
     * 服务名称
     *
     * @var string
     */
    public $serviceName;

    /**
     * 服务接口
     *
     * @var string
     */
    public $interface;

    /**
     * 获取注入值的真实值
     *
     * @return mixed
     */
    public function getRealValue()
    {
        /** @var \Imi\Grpc\Client\GrpcClient $client */
        $client = RpcClientPool::getInstance($this->poolName);
        return $client->getService($this->serviceName, $this->interface);
    }

}
