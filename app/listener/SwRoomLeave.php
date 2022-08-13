<?php
declare (strict_types = 1);

namespace app\listener;

use think\Container;
use think\swoole\Websocket;

class SwRoomLeave
{
    public $websocket = null;

    public function __construct(Container $container)
    {
        $this->websocket = $container->make(Websocket::class);
    }
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        $this->websocket->to($event['room'])->emit("leave", ['msg' => '客户端编号:'.$this->websocket->getSender().'离开了房间。']);
        $this->websocket->leave($event['room']);
    }
}
