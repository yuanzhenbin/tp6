<?php
declare (strict_types = 1);

namespace app\listener;

use think\Container;
use think\swoole\Websocket;
use think\swoole\websocket\Room;

class SwWsConnect
{
    public $websocket = null;

    public function __construct(Container $container)
    {
        $this->websocket = $container->make(Websocket::class);
        $this->room = $container->make(Room::class);
    }
    /**
     * 事件监听处理
     *
     * @return mixed
     * 受用 WebSocket 客户端连接入口
     */
    public function handle($event)
    {
        var_dump($event);
        $data = [
            'msg' => "有人悄悄上线了..."
        ];
        $this->websocket->broadcast()->emit('connect',$data);
    }
}
