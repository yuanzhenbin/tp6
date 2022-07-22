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
            $ret = Queue::later(20,$job_class_name,$queue_data,$job_queue_name);
            if ($ret) {
                return_ajax([],200,'发送成功');
            } else {
                return_ajax([],0,'发送失败');
            }
        } else {
            return_ajax([],0,'发送失败');
        }
    }
}
