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

class RedisPsubscribe extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('myqueue')
            ->setDescription('the myqueue command');
    }

    protected function execute(Input $input, Output $output)
    {
        $redis = new Redis();
        //设置订阅超时时间 默认60s之后断开连接
        $redis->setOption(\Redis::OPT_READ_TIMEOUT, -1);
        echo "-----过期订阅-----\n";
        $redis->psubscribe(array('__keyevent@0__:expired'), function($redis, $pattern, $chan, $msg) {
            data_log(2,json_encode([$msg]),'过期订阅');
            var_dump($pattern, $chan, $msg);
        });
    }
}
