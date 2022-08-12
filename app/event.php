<?php
// 事件定义文件
return [
    'bind'      => [
        'EventTest' => \app\event\EventTest::class,
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'EventTest' => [
            \app\listener\EventTest::class,
        ],
    ],

    'subscribe' => [
        'SubscribeTest' => \app\subscribe\SubscribeTest::class,
    ],
];
