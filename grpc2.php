<?php

use GPBMetadata\Grpc;
use Grpc\LoginRequest;
use Grpc\LoginResponse;
use Imi\Grpc\Client\GrpcClient;
require __DIR__ . '/vendor/autoload.php';

go(function(){
    $client = new GrpcClient([
        'url'   =>  'http://127.0.0.1:8080/testLogin?phone=12345678901&password=123456',
    ]);
    
    var_dump($client->open());
    
    $request = new LoginRequest;
    $request->setPhone('12345678901');
    $request->setPassword('123456');
    var_dump($streamId = $client->send('', '', '', $request));
    
    /** @var LoginResponse $response */
    $response = $client->recv(LoginResponse::class, $streamId);
    var_dump($response->getSuccess(), $response->getError());
    
    $client->close();
});