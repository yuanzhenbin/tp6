<?php
declare (strict_types = 1);

namespace app\listener;

class SwRoomJoin
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event, \think\swoole\websocket $ws, \think\swoole\websocket\room $room)
    {
        $fd = $ws->getSender();
        //客户端假如定的room
        $roomid = $event['room'];
        //获取指定房间下有哪些客户端
        $roomfds = $room->getClients($roomid);
        // 判断这个房间有没有自己 如果有自己就不需要再次发送通知
        if (in_array($fd, $roomfds)) {
            $ws->to($roomfds)->emit("roomjoincallback", "房间{$roomid}已加入");
            return;
        }
        //加入房间
        $ws->join($roomid);
        $ws->to($roomfds)->emit("roomjoincallback", "{$fd}加入房间{$roomid}成功");
    }
}
