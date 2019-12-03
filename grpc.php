<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * service Xuexitest{}
 * 编写 (gprc 定义 Xuexitest 服务)的客户端
 */
class XuexitestClient extends \Grpc\BaseStub{

    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * rpc SayTest(TestRequest) returns (TestReply) {}
     * 方法名尽量和 (gprc 定义 Xuexitest 服务)的方法一样
     * 用于请求和响应该服务
     */
    public function SayTest(\Grpc\LoginRequest $argument,$metadata=[],$options=[]){
        // (/xuexitest.Xuexitest/SayTest) 是请求服务端那个服务和方法，基本和 proto 文件定义一样
        // (\Xuexitest\TestReply) 是响应信息（那个类），基本和 proto 文件定义一样
        return $this->_simpleRequest('/',
            $argument,
            [\Grpc\LoginResponse::class, 'decode'],
            $metadata, $options);
    }

}

//用于连接 服务端
$client = new \XuexitestClient('127.0.0.1:8080', [
    'credentials' => Grpc\ChannelCredentials::createInsecure()
]);

//实例化 TestRequest 请求类
$request = new \Grpc\LoginRequest();
$request->setPhone('111');
$request->setPassword('222');

//调用远程服务
$get = $client->SayTest($request, ['key' => ['value', '222']])->wait();

//返回数组
//$reply 是 TestReply 对象
//$status 是数组
/** @var \Grpc\LoginResponse $reply */
list($reply, $status) = $get;

// //数组
// $getdata = $reply->getGetdataarr();

// foreach ($getdata as $k=>$v){
//     echo $v->getId(),'=>',$v->getName(),"\n\r";
// }

var_dump($reply, $status);
var_dump($reply->getError(), $reply->getSuccess());