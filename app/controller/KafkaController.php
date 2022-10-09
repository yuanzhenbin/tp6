<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class KafkaController extends BaseController
{
    public static $brokerList = 'localhost:9092';
    public function index()
    {
        return View::fetch();
    }

    //生产者
    public static function producer($message, $topic = 'test')
    {
        $conf = new \RdKafka\Conf();
        $conf->set('metadata.broker.list', self::$brokerList);
        $producer = new \RdKafka\Producer($conf);
        $topic = $producer->newTopic($topic);
//        while (true) {
//            echo "\nEnter  messages:\n";
//            //发消息
//            $topic->produce(RD_KAFKA_PARTITION_UA, 0, 'message:'.$message);
//        }

        for ($i=1; $i<=3; $i++){
            //发消息
            sleep(1);
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, 'message:'.$message);
        }

        return_ajax([],200,'done');
    }

    //消费者
    public function consumer()
    {
        $topic = input('topic','test');
        if (!$topic) {
            return_ajax([],0,'话题不能为空');
        }
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', 'myConsumerGroup');
        $conf->set('enable.partition.eof', 'true');

        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers("localhost:9092");

        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('auto.commit.interval.ms', '100');
        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'earliest');

        $topic = $rk->newTopic("test", $topicConf);
        $topic->consumeStart(0, RD_KAFKA_OFFSET_END);//RD_KAFKA_OFFSET_STORED

        while (true) {
            // 第一个参数是分区，第二个参数是超时时间
            $message = $topic->consume(0, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    var_dump($message->payload);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "等待新消息\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "超时\n";
                    break;
                default:
                    //错误信息
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }
    }

    //发送消息
    public function send()
    {
        $message = input('message','');
        $topic = input('topic','test');
        if (!$topic) {
            return_ajax([],0,'话题不能为空');
        }
        self::producer($message, $topic);
    }
}
