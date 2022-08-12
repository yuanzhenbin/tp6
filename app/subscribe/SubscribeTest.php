<?php
declare (strict_types = 1);

namespace app\subscribe;
use think\Event;

class SubscribeTest
{
    public function onEventTestLogin(\app\event\EventTest $event)
    {
        echo "onEventTestLogin<br>";
        $event->hello();
    }

    public function onEventTestOut(\app\event\EventTest $event)
    {
        echo "onEventTestOut<br>";
    }

    public function onTestLogin()
    {
        echo "onTestLogin<br>";
    }

    public function onTestOut()
    {
        echo "onTestOut<br>";
    }

    //打开自定义订阅之后，除非符合subscribe内监听的事件，否则不触发 也就是下面这样，一行输出都没有
//    public function subscribe(Event $event)
//    {
////        $event->listen('EventTestLogin', [$this,'onEventTestLogin']);
////        $event->listen('EventTestOut',[$this,'onEventTestOut']);
//
////        $event->listen('TestLogin', [$this,'onTestLogin']);
////        $event->listen('TestOut',[$this,'onTestOut']);
//    }
}
