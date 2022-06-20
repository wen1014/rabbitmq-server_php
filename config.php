<?php
return $msmqConfig = [
    'host'     => '127.0.0.1',
    'port'     => 5672,
    'username' => 'guest',
    'password' => 'guest',
    'vhost'    => '/',
    'exchange' => [
        //键==交换机名称
        'pd_file_exchange_test'        => [
            //交换器类型
            'type'        => 'direct', //直连交换机.
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否自动删除
            'auto_delete' => false
        ],
        //文件交换机的死信交换机
        'dead.letter.pd_file_exchange_test'        => [
            //交换器类型
            'type'        => 'direct', //直连交换机.
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否自动删除
            'auto_delete' => false
        ],
        'pd_user_exchange_test'        => [
            //交换器类型
            'type'        => 'fanout', //扇形交换机
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否自动删除
            'auto_delete' => false
        ],
        'system_log_exchange_test' => [
            //交换器类型
            'type'        => 'topic', //主题交换机
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否自动删除
            'auto_delete' => false
        ]
    ],
    //is_retry = 是否重试
    //is_dead => true, = 是否是死信队列

    'queue'    => [
        //队列名称
        'pd_file_create_queue_test' => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //是否重试
            'is_retry' => true,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [],
        ],
        'pd_file_log_queue_test'    => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [
                //投递到哪个死信交换机
                'x-dead-letter-exchange' => 'dead.letter.pd_file_exchange_test',
                //x-dead-letter-routing-key参数不填默认为之前的路由.
            ],
        ],
        'dead.letter.pd_file_log_queue_test'    => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //是否是死信队列
            'is_dead' => true,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [],
        ],
        'pd_user1_queue_test'       => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [],
        ],
        'pd_user2_queue_test'       => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [],
        ],
        'system_log_queue_test'       => [
            //是否被动
            'passive'     => false,
            //是否持久化
            'durable'     => true,
            //是否排他
            'exclusive'   => false,
            //是否自动删除
            'auto_delete' => false,
            //设置队列的其他一些参数，如 x-message-ttl、x-expires、x-max-length。
            'arguments'   => [],
        ],
    ],
    'msmq'     => [
        //文件创建的消息队列.
        'pd_file_create' => [
            //交换机名称 对应上面的queue数组
            'exchange_name' => 'pd_file_exchange_test',
            //队列名称.
            'queue_name'    => 'pd_file_create_queue_test',
            //路由键
            'routing_key'   => 'file_create',
        ],
        //文件操作日志的消息队列.
        'pd_file_log'    => [
            //交换机名称 对应上面的exchange数组
            'exchange_name' => 'pd_file_exchange_test',
            //队列名称.对应上面的queue数组
            'queue_name'    => 'pd_file_log_queue_test',
            //路由键
            'routing_key'   => 'file_log',
        ],
        //文件操作日志的死信队列.
        'dead.letter.pd_file_log'    => [
            //交换机名称 对应上面的exchange数组
            'exchange_name' => 'dead.letter.pd_file_exchange_test',
            //队列名称.对应上面的queue数组
            'queue_name'    => 'dead.letter.pd_file_log_queue_test',
            //路由键
            'routing_key'   => 'file_log',
        ],
        //用户队列1.
        'user_list1'     => [
            //交换机名称 对应上面的exchange数组
            'exchange_name' => 'pd_user_exchange_test',
            //队列名称.对应上面的queue数组
            'queue_name'    => 'pd_user1_queue_test',
            //路由键 [扇形交换机 路由键不生效]
            'routing_key'   => 'no____no',
        ],
        //用户队列2
        'user_list2'     => [
            //交换机名称 对应上面的exchange数组
            'exchange_name' => 'pd_user_exchange_test',
            //队列名称.对应上面的queue数组
            'queue_name'    => 'pd_user2_queue_test',
            //路由键 [扇形交换机 路由键不生效]
            'routing_key'   => 'no____no',
        ],
        //系统日志队列.[主题交换机类型]
        'system_log'     => [
            //交换机名称 对应上面的exchange数组
            'exchange_name' => 'system_log_exchange_test',
            //队列名称.对应上面的queue数组
            'queue_name'    => 'system_log_queue_test',
            //路由键  ..必须以,分割
            'routing_key'   => 'userModule.warning,payModule.*',
        ],
    ]
];