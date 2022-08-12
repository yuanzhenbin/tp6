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

class SeckillController extends BaseController
{
    public function index()
    {
        View::assign('stock',100);
        return View::fetch();
    }

    public function send()
    {
        $goods_info = input('goods_info','商品信息');
        $redis = Cache::store('redis');
        $redis->watch('sales');
        $sales = $redis->get('sales');

        $n = 100;
        if ($sales >= $n) {
            return_ajax([],0,'秒杀结束');
        }

        //开始事务 到执行事务之前，其他客户端提交的命令请求不会插入到事务执行命令序列中
        $redis->multi();
        //命令入队 sales+1
        $redis->incr('sales');
        //执行事务
        $res = $redis->exec();
        if ($res) {
            //这里为了记录数据写进库里，实际场景跳过库的写操作，直接存到消息队列然后返回结果
            $log_data = [
                'uid'=>session('uid'),
                'uname'=>session('uname'),
                'time'=>date('Y-m-d H:i:s', time()),
                'sales'=>$sales,
            ];
            Db::name('log')->insert(['type'=>2,'content'=>json_encode($log_data),'create_time'=>time(),'title'=>'请求秒杀']);

            //php think queue:work --queue seckillQueueOne
            $job_class_name = 'app\controller\queue\Seckill';
            $job_queue_name = 'seckillQueueOne';
            $queue_data = [
                'uid' => session('uid'),
                'uname' => session('uname'),
                'goods_info' => $goods_info,
                'time'=>date('Y-m-d H:i:s', time()),
                'sales'=>$sales+1,
            ];
            $ret = Queue::push($job_class_name,$queue_data,$job_queue_name);
            if (!$ret) {
                return_ajax([],0,'加入消息队列失败');
            }

            //加入消息队列成功之后直接返回结果，业务逻辑由seckill处理 可以使用later延时，也可以等到并发低了再调用seckill
            return_ajax([],200,'秒杀成功');
        } else {
            return_ajax([],0,'秒杀失败');
        }
    }

    public function clear()
    {
//        $redis = New Redis();
        $redis = Cache::store('redis');
        $redis->handler()->del('sales');

        return_ajax([],200,'清除成功');
    }

    //获取存货
    public function getStock()
    {

    }
}
