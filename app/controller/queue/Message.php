<?php
namespace app\controller\queue;

use app\BaseController;
use think\cache\driver\Redis;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\exception\ValidateException;
use think\facade\Queue;
use think\queue\Job;

class Message extends BaseController
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array $data 发布任务时自定义的数据
     */
    public function fire(Job $job, array $data)
    {
        //去重
//        $isHas = $this->checkData($data);
//        if ($isHas) {
//            $job->delete();
//            return;
//        }

        $isJobDone = $this->doJob($data);
        if ($isJobDone) {
            $job->delete();
        } else {
            if ($job->attempts() > 3) {
                $job->delete();
                //状态 失败
                Db::name('message')->where('id',$data['message_id'])->update(['status' => 3]);
                //记录日志
                Db::name('log')->insert(['type'=>1,'content'=>'任务失败次数超过上限|'.json_encode($data),'create_time'=>time()]);
            }
        }
    }

    /**
     * 任务去重
     * @param array $data
     * @return bool
     */
    private function checkData(array $data)
    {
        return false;
    }

    /**
     * 业务处理
     * @param array $data
     * @return bool
     */
    private function doJob(array $data)
    {
        $message_data = Db::name('message')->where('id',$data['message_id'])->find();
        if (!$message_data) {
            return false;
        }
        if (!isset($message_data['status']) || $message_data['status'] != 1) {
            return false;
        }
        $message = $message_data['message'];
        $uid = $data['uid'];
        $name = $data['uname'];

        //执行发送消息
        $ret = true;

        //发送成功修改信息状态
        Db::name('message')->where('id',$data['message_id'])->update(['status' => 2, 'send_time' => time()]);
        data_log(2,'消息队列执行成功','messageQueueOne 出队');

        if($ret !== false) {
            return true;
        } else {
            return false;
        }
    }

}
