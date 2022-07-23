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

class Seckill extends BaseController
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
                //记录日志
                Db::name('log')->insert(['type'=>1,'content'=>'Seckill|任务失败次数超过上限|'.json_encode($data),'create_time'=>time()]);
            } else {
                //记录日志
                Db::name('log')->insert(['type'=>1,'content'=>'Seckill|代码报错','create_time'=>time()]);
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
        try {
            $content = '商品[' . $data['goods_info'] . '] 排序['.$data['sales'].'] 购买人[' . $data['uname'] . '] 时间[' . $data['time'] . ']';

            //处理订单、商品信息

            //发送成功修改信息状态
            $ret = Db::name('log')->insert(['type' => '2', 'content' => $content, 'create_time' => time(),'title'=>'秒杀']);

            if ($ret !== false) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

}
