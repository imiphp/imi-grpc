<?php
namespace ImiApp\ApiServer\Controller;

use Grpc\LoginRequest;
use Grpc\LoginResponse;
use Imi\Controller\HttpController;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Controller;

/**
 * @Controller("/")
 */
class IndexController extends HttpController
{
    /**
     * @Action
     * @Route("/")
     *
     * @return void
     */
    public function index(LoginRequest $request)
    {
        var_dump($request->getPhone(), $request->getPassword());
        // return [
        //     'hello' =>  'imi',
        //     'time'  =>  date('Y-m-d H:i:s', time()),
        // ];
        $response = new LoginResponse;
        $response->setSuccess(true);
        $response->setError('123');
        return $response;
    }

    /**
     * @Action
     * @return void
     */
    public function api($time)
    {
        return [
            'hello' =>  'imi',
            'time'  =>  date('Y-m-d H:i:s', time()),
        ];
    }

}
