<?php
namespace app\controller;

use app\BaseController;
use think\cache\driver\Redis;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;

class RedisTestController extends BaseController
{
    public function index()
    {
        Cache::store('redis')->set('name','redistest',5);
        Cache::store('redis')->set('name1','key1');
        Cache::set('name2','rediscache',4);

        dump(
            '---------------------------------0s:---------------------------------',
            'name='.Cache::store('redis')->get('name'),
            'name1='.Cache::store('redis')->get('name1'),
            'name2='.Cache::get('name2')
        );

        Cache::store('redis')->delete('name1');
        sleep(5);

        dump(
            '---------------------------------5s:---------------------------------',
            'name='.Cache::store('redis')->get('name'),
            'name1='.Cache::store('redis')->get('name1'),
            'name2='.Cache::get('name2')
        );
    }

    public function test()
    {
        //下面两种效果是一样的
        //直接调用redis驱动
        //$redis = new Redis();
        //通过框架提供的缓存类调用redis
        $redis = Cache::store('redis');

        //清除缓存 实际场景慎用
        $redis->clear();


        echo 'string';
        echo '<br/>';
        $redis->set('k1','v1');
        $redis->set('k2','v2',30);
        $redis->setnx('k1','new v1');
        //todo 这里的strlen如果value包含非数字，会在原长度加8，在redis里直接测试没有这个现象
        echo $redis->strlen('k1');
        echo '<br/>';
        echo $redis->get('k1');
        echo '<br/>';
        echo $redis->get('k2');
        echo '<br/>';
        echo $redis->ttl('k2');
        echo '<br/>';
        echo '<br/>';


        echo 'hash';
        echo '<br/>';
        $redis->hset('h1','f1','h1v1');
        $redis->hset('h1','f2','h1v2');
        $redis->hset('h1','f3','h1v3');
        $redis->hset('h2','f1','h2v1');
        echo $redis->hlen('h1');
        echo '<br/>';
        echo $redis->hget('h1','f1');
        echo '<br/>';
        echo $redis->hget('h1','f2');
        echo '<br/>';
        echo $redis->hget('h1','f3');
        echo '<br/>';
        echo $redis->hget('h2','f1');
        echo '<br/>';
        echo '<br/>';


        echo 'list';
        echo '<br/>';
        $redis->lpush('l1','v1');
        $redis->lpush('l1','v2');
        $redis->lpush('l1','v3');
        $redis->rpush('l1','v0');
        echo $redis->llen('l1');
        echo '<br/>';
        var_dump($redis->lrange('l1',0,-1));
        echo '<br/>';
        echo $redis->lpop('l1');
        echo '<br/>';
        echo $redis->rpop('l1');
        echo '<br/>';
        echo '<br/>';


        echo 'set';
        echo '<br/>';
        $redis->sadd('s1','s1v1','s1v2','s1v3');
        echo $redis->scard('s1');
        echo '<br/>';
        var_dump($redis->sRem('s1','s1v1'));
        echo '<br/>';
        var_dump($redis->smembers('s1'));
        echo '<br/>';
        echo '<br/>';
        echo '<br/>';


        echo 'zset';
        echo '<br/>';
        $redis->zadd('z1',100,'v1',200,'v2',500,'v3');
        echo $redis->zcard('z1');
        echo '<br/>';
        var_dump($redis->zrange('z1',0,-1));
        echo '<br/>';
        echo '<br/>';
        echo '<br/>';


        echo 'cache';
        echo '<br/>';
        cache('c1','助手函数');
        echo cache('c1');
        echo '<br/>';
        var_dump(cache('c2'));
        echo '<br/>';
    }
}
