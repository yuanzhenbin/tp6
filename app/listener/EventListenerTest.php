<?php
declare (strict_types = 1);

namespace app\listener;

class EventListenerTest
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle(\app\event\EventTest $event)
    {
        echo "这里是EventListenerTest<br>";
        echo "这里是EventListenerTest监听EventTest得到:" . $event->login_event() . "<br>";
        $event->hello();
    }
}
