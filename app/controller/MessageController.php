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

class MessageController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function send()
    {
        $message = input('message');
        if (!$message) {
            return_ajax([],0,'消息不能为空');
        }

        $data = [
            'uid' => session('uid'),
            'uname' => session('uname'),
            'message' => $message,
            'create_time' => time(),
            'status' => 1
        ];

        $message_id = Db::name('message')->insertGetId($data);
        if ($message_id) {
            $queue_data = [
                'uid' => session('uid'),
                'uname' => session('uname'),
                'message_id' => $message_id,
            ];
            //php think queue:work --queue messageQueueOne
            $job_class_name = 'app\controller\queue\Message';
            $job_queue_name = 'messageQueueOne';
//            $ret = Queue::later(10,$job_class_name,$queue_data,$job_queue_name);//延迟10s
            $ret = Queue::push($job_class_name,$queue_data,$job_queue_name);//无延迟
            if ($ret) {
                data_log(2,'消息队列执行成功','messageQueueOne 入队');
                return_ajax([],200,'发送成功');
            } else {
                return_ajax([],0,'发送失败');
            }
        } else {
            return_ajax([],0,'发送失败');
        }
    }

    //自定义消息队列 入队
    public function myQueue()
    {
        $message = input('message');
        if (!$message) {
            return_ajax([],0,'消息不能为空');
        }

        $data = [
            'uid' => session('uid'),
            'uname' => session('uname'),
            'message' => $message,
            'create_time' => time(),
            'status' => 1
        ];

        $message_id = Db::name('message')->insertGetId($data);
        if ($message_id) {
            $queue_data = [
                'uid' => session('uid'),
                'uname' => session('uname'),
                'message_id' => $message_id,
            ];
            $job_queue_name = 'messageQueueTwo';
            $redis = Cache::store('redis');
            $ret = $redis->lpush($job_queue_name,json_encode($queue_data));
            //php think myQueue 使用tp6脚本出队
            if ($ret) {
                data_log(2,'自定义消息队列执行成功','messageQueueTwo 入队');
                return_ajax([],200,'发送成功');
            } else {
                return_ajax([],0,'发送失败');
            }
        } else {
            return_ajax([],0,'发送失败');
        }
    }

    //自定义消息队列 出队
    public function getMyQueue()
    {
        $wait_time = input('wait_time', 0);
        $sort = input('sort','asc');
        if ($sort == 'asc') {
            $direction = 'rpop';
        } else {
            $direction = 'lpop';
        }

        $redis = Cache::store('redis');
        while($check = $redis->$direction('messageQueueTwo')) {
            $data = json_decode($check,true);
            $message_data = Db::name('message')->where('id',$data['message_id'])->find();
            if (!$message_data) {
                continue;
            }
            if (!isset($message_data['status']) || $message_data['status'] != 1) {
                continue;
            }

            Db::name('message')->where('id',$data['message_id'])->update(['status' => 2, 'send_time' => time()]);
            data_log(2,'自定义消息队列执行成功|'.$check,'messageQueueTwo 出队');
            sleep((int)$wait_time);
        }

        return_ajax([],200,'执行成功');
    }
}
