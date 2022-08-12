<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Event;
use app\service\MyService;

class TestEventController extends BaseController
{
    public function index()
    {
        echo "这里是TestEvent控制器的index方法<br>";
        echo "<br>";

        //下面这两个都不会生效 事件是没反应的 监听事件才能触发
        event('EventTest');
        Event::trigger('EventTest');

        //事件类
        //触发监听事件 下面两种作用一样
        event('EventListenerTest');
        echo "<br>";
        Event::trigger('EventListenerTest');
        echo "<br>";

        //注册的事件
        Event::listen('MyListener',function (\app\event\EventTest $event){
            echo "这里是MyListener<br>";
            echo "这里是MyListener监听EventTest得到:" . $event->login_event() . "<br>";
        });
        Event::trigger('MyListener');
        echo "<br>";

        //事件订阅
        Event::trigger('EventTestLogin');
        Event::trigger('TestLogin');
    }
}
