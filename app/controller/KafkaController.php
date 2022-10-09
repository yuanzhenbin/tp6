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
    public static $brokerList = '127.0.0.1:9002';
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
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($message));
        $producer->poll(0);
        $result = $producer->flush(10000);
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new \RuntimeException('Was unable to flush, messages might be lost!');
        }
    }

    //消费者
    public function consumer()
    {
        $topic = input('topic','test');
        if (!$topic) {
            return_ajax([],0,'话题不能为空');
        }
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', $topic);
        $rk = new \RdKafka\Consumer($conf);
        $rk->addBrokers("127.0.0.1");
        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('auto.commit.interval.ms', 100);
        $topicConf->set('offset.store.method', 'broker');
        $topicConf->set('auto.offset.reset', 'smallest');
        $topic = $rk->newTopic($topic, $topicConf);
        $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);
        while (true) {
            $message = $topic->consume(0, 120*10000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    var_dump($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break;
                default:
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
