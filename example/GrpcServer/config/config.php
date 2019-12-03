<?php

use Imi\Log\LogLevel;
return [
    'configs'    =>    [
    ],
    // bean扫描目录
    'beanScan'    =>    [
        'ImiApp\GrpcServer\Controller',
    ],
    'beans'    =>    [
        'HttpDispatcher'    =>    [
            'middlewares'    =>    [
                \ImiApp\GrpcServer\Middleware\PoweredBy::class,
                \Imi\Server\Http\Middleware\RouteMiddleware::class,
            ],
        ],
        'ConnectContextStore'   =>  [
            'handlerClass'  =>  'ConnectContextMemoryTable',
        ],
        'ConnectContextMemoryTable' =>  [
            'tableName' =>  'connectContext',
        ],
        'ActionWrapMiddleware'  =>  [
            'actionMiddleware'  =>  'GrpcActionMiddleware',
        ],
    ],
];