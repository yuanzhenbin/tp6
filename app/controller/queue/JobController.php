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

class JobController extends BaseController
{
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array $data 发布任务时自定义的数据
     */
    public function fire(Job $job, array $data)
    {
        echo '访问成功 ';
        //去重
        $isHas = $this->checkData($data);
        if ($isHas) {
            $job->delete();
            return;
        }

        $isJobDone = $this->doJob($data);
        if ($isJobDone) {
            $job->delete();
            echo "删除任务" . $job->attempts() . '\n';
        } else {
            if ($job->attempts() > 3) {
                $job->delete();
                echo "任务失败次数超过上限，删除" . $job->attempts() . '\n';
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
        if (cache($data['account'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 业务处理
     * @param array $data
     * @return bool
     */
    private function doJob(array $data)
    {
        $ret = Db::name('log')->insert(['type'=>1,'content'=>json_encode($data),'create_time'=>time()]);

        if($ret !== false) {
            return true;
        } else {
            return false;
        }
    }

}
