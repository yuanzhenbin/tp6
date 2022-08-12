<?php
declare (strict_types = 1);

namespace app\event;

class EventTest
{
    public function __construct()
    {
        echo "这里是EventTest构造方法<br>";
    }

    //'listen'    => [
    //        'EventListenerTest' => [
    //            \app\event\EventTest::class,
    //        ],
    //    ],
    //如果这么定义， 就会直接走到这个方法
    public function handle(\app\event\EventTest $event)
    {
        echo "这里是EventListenerTest<br>";
        echo "这里是EventListenerTest监听EventTest得到:" . $event->login_event() . "<br>";
        $event->hello();
    }

    public function login_event()
    {
        echo "这里是EventTest的login_event方法<br>";
    }

    public function hello()
    {
        echo "hello ".session('uname')." !<br>";
    }
}
