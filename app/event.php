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
        'EventListenerTest' => [
            \app\listener\EventListenerTest::class,
        ],
//        // 监听链接
//        'swoole.websocket.Connect' => [
//            \app\listener\SwWsConnect::class
//        ],
//        //关闭连接
//        'swoole.websocket.Close' => [
//            \app\listener\SwWsClose::class
//        ],
//        //发送消息场景
//        'swoole.websocket.Message' => [
//            \app\listener\SwWsMessage::class
//        ],
//        // 加入房间
//        'swoole.websocket.RoomJoin' => [
//            \app\listener\SwRoomJoin::class
//        ],
//        // 离开房间
//        'swoole.websocket.Roomleave' => [
//            \app\listener\SwRoomLeave::class
//        ],
    ],

    'subscribe' => [
        'SubscribeTest' => \app\subscribe\SubscribeTest::class,
    ],
];
