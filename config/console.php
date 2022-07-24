<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'myQueue' => \app\command\MyQueue::class,
        'redisSub' => \app\command\RedisSubscribe::class,
        'redisPsub' => \app\command\RedisPsubscribe::class,
    ],
];
