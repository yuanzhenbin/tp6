<?php
namespace app\controller;

use app\BaseController;
use think\cache\driver\Redis;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;

class RedisPublishController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function send()
    {
        $message = input('message');
        if (!$message) {
            return_ajax([],0,'信息不能为空');
        }

        //php think redisSub
        $redis = Cache::store('redis');
        $channel = 'redis_channel1';
        $redis->publish($channel,(string)session('uname').':'.$message);
        return_ajax([],200,'发布成功');
    }

    //psubscribe 过期事件订阅
    public function setExpire()
    {
        //php think redisSub
        $redis = Cache::store('redis');
        $redis->setex('setExpire1',10,'10s');
        $redis->setex('setExpire2',15,'15s');
        $redis->setex('setExpire3',20,'20s');

        return_ajax([],200,'设置成功');
    }
}
