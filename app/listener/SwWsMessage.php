<?php
declare (strict_types = 1);

namespace app\listener;

class SwWsMessage
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event, \think\swoole\websocket $ws)
    {
        $fd = $ws->getSender();
        $data = json_encode($event);
        echo "{$fd}:{$data}<br>";
        $ws->emit("this is server", $fd);
    }
}
