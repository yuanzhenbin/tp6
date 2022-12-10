<?php
namespace app\controller;

use app\BaseController;
use GatewayClient\Gateway;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class RabbitMqController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    //客户端发言
    public function send()
    {
        $message = input('message');
        if (!$message) {
            return_ajax([],400,'消息为空');
        }
        $exchangeName = 'demo';
        $routeKey = 'hello';
        $count = 0;
        // 建立TCP连接
        $connection = new \AMQPConnection(config('protected.rabbitMq'));
        $connection->connect() or die("Cannot connect to the broker!\n");
        try {
            $channel = new \AMQPChannel($connection);
            $exchange = new \AMQPExchange($channel);

            $exchange->setName($exchangeName);
            $exchange->setType(AMQP_EX_TYPE_DIRECT);
            $exchange->declareExchange();

            $count = $exchange->publish($message, $routeKey);
        } catch (AMQPConnectionException $e) {
            return_ajax([],500,'error');
        }

        $connection->disconnect();// 断开连接
        return_ajax([],200,'send message '.$count);

        //php think rabbitmq 使用tp6脚本出队
    }
}