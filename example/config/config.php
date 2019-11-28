<?php
return [
    // 项目根命名空间
    'namespace'    =>    'ImiApp',

    // 配置文件
    'configs'    =>    [
        'beans'        =>    __DIR__ . '/beans.php',
    ],

    // 扫描目录
    'beanScan'    =>    [
        'ImiApp\Listener',
        'ImiApp\Task',
    ],

    // 组件命名空间
    'components'    =>  [
        'Grpc'  =>  'Imi\Grpc',
    ],

    // 主服务器配置
    'mainServer'    =>    [
        'namespace'    =>    'ImiApp\ApiServer',
        'type'        =>    'Grpc',
        'host'        =>    '127.0.0.1',
        'port'        =>    8080,
        'configs'    =>    [
            // 'worker_num'        =>  8,
            // 'task_worker_num'   =>  16,
            'open_http2_protocol'   =>  true,
        ],
    ],

    // 子服务器（端口监听）配置
    'subServers'        =>    [
        // 'SubServerName'   =>  [
        //     'namespace'    =>    'ImiApp\XXXServer',
        //     'type'        =>    Imi\Server\Type::HTTP,
        //     'host'        =>    '127.0.0.1',
        //     'port'        =>    13005,
        // ]
    ],

    // 连接池配置
    'pools'    =>    [
        'redis'    =>    [
            'sync'    =>    [
                'pool'    =>    [
                    'class'        =>    \Imi\Redis\SyncRedisPool::class,
                    'config'    =>    [
                        'maxResources'    =>    10,
                        'minResources'    =>    0,
                    ],
                ],
                'resource'    =>    [
                    'host'      => '127.0.0.1',
                    'port'      => 6379,
                    'password'  => null,
                ]
            ],
            'async'    =>    [
                'pool'    =>    [
                    'class'        =>    \Imi\Redis\CoroutineRedisPool::class,
                    'config'    =>    [
                        'maxResources'    =>    10,
                        'minResources'    =>    1,
                    ],
                ],
                'resource'    =>    [
                    'host'      => '127.0.0.1',
                    'port'      => 6379,
                    'password'  => null,
                ]
            ],
        ],
    ],

    // 数据库配置
    'db'    =>    [
        // 数默认连接池名
        'defaultPool'    =>    'maindb',
    ],

    // redis 配置
    'redis' =>  [
        // 数默认连接池名
        'defaultPool'   =>  'redis',
    ],

    // 内存表配置
    'memoryTable'   =>  [
        'connectContext'    =>  [
            'class' =>  \Imi\Server\ConnectContext\StoreHandler\MemoryTable\ConnectContextOption::class,
            'lockId'=>  'redisConnectContextLock',
            'size'  =>  65536,
        ],
    ],

    // 锁
    'lock'  =>[
        'list'  =>  [
            'redisConnectContextLock' =>  [
                'class' =>  'RedisLock',
                'options'   =>  [
                    'poolName'  =>  'redis',
                ],
            ],
        ],
    ],
];