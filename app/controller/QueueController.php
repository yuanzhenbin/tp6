<?php
namespace app\controller;

use app\BaseController;
use think\cache\driver\Redis;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;
use think\facade\Queue;

class QueueController extends BaseController
{
    public function index()
    {
        $data = Db::name('user')->limit(1,10)->select()->toArray();
        //控制器要写全名
        $job_class_name = 'app\controller\queue\JobController';
        $job_queue_name = 'jobQueueOne';

        //php think queue:work --queue jobQueueOne
        $ret = Queue::push($job_class_name,['name'=>'test','id'=>1],$job_queue_name);
        if ($ret) {
            echo '推送成功';
        } else {
            echo '推送失败';
        }

//        foreach ($data as $v) {
//            //延迟执行
////            Queue::later(60,$job_class_name,$v,$job_queue_name);
//            //立即执行
//            Queue::push($job_class_name,$v,$job_queue_name);
//        }
    }
}
