<?php
declare (strict_types = 1);

namespace app\listener;

class SwWsConnect
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event, \think\swoole\websocket $ws)
    {
        // 获取当前发送者的fd
        $fd = $ws->getSender();
        echo "server: handshake success with fd{$fd}\n";
    }
}
