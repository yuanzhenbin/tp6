<?php
declare (strict_types = 1);

namespace app\command;

use think\cache\driver\Redis;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use think\facade\Cache;

class RedisSubscribe extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('myqueue')
            ->setDescription('the myqueue command')
            ->addArgument('sort')
            ->addArgument('wait_time');
    }

    protected function execute(Input $input, Output $output)
    {
//        $redis = Cache::store('redis');
        $redis = new Redis();
        //设置订阅超时时间 默认60s之后断开连接
        //踩坑 过期事件要去redis修改配置 notify-keyspace-events Ex
        $redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
        $channel = 'redis_channel1';
        echo "-----订阅".$channel."频道-----\n";
        $redis->subscribe([$channel], function($redis, $channel, $msg) {
            data_log(2,$msg,'发布订阅');
            echo $msg."\n";
        });
    }
}
