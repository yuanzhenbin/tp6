<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use think\facade\Cache;

class myQueue extends Command
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
        $wait_time = $input->getArgument('wait_time')?:0;
        $sort = $input->getArgument('sort')?:'asc';
        if ($sort == 'asc') {
            $direction = 'rpop';
        } else {
            $direction = 'lpop';
        }

        $redis = Cache::store('redis');
        while($data = $redis->$direction('messageQueueTwo')) {
            $data = json_decode($data,true);
            $message_data = Db::name('message')->where('id',$data['message_id'])->find();
            if (!$message_data) {
                continue;
            }
            if (!isset($message_data['status']) || $message_data['status'] != 1) {
                continue;
            }

            Db::name('message')->where('id',$data['message_id'])->update(['status' => 2, 'send_time' => time()]);

            sleep((int)$wait_time);
        }

        Db::name('log')->insert(['type'=>2,'content'=>'执行成功','create_time'=>time(),'title'=>'myQueue']);

        // 指令输出
        $output->writeln('执行成功');
    }
}
