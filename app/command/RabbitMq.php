<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class RabbitMq extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('rabbitmq')
            ->setDescription('the rabbitmq command');
    }

    protected function execute(Input $input, Output $output)
    {
        $exchangeName = 'demo';
        $queueName = 'hello';
        $routeKey = 'hello';
        // 建立TCP连接
        $connection = new \AMQPConnection(config('protected.rabbitMq'));
        $connection->connect() or die("Cannot connect to the broker!\n");

        $channel = new \AMQPChannel($connection);
        $exchange = new \AMQPExchange($channel);
        $exchange->setName($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);

        echo 'Exchange Status: ' . $exchange->declareExchange() . "\n";
        $queue = new \AMQPQueue($channel);
        $queue->setName($queueName);
        echo 'Message Total: ' . $queue->declareQueue() . "\n";
        echo 'Queue Bind: ' . $queue->bind($exchangeName, $routeKey) . "\n";
        var_dump("Waiting for message...");
        // 消费队列消息
        while(TRUE) {
            $queue->consume(function ($envelope, $queue) {
                $msg = $envelope->getBody();
                var_dump("Received: " . $msg);
                $queue->ack($envelope->getDeliveryTag()); // 手动发送ACK应答
            });
        }

        // 断开连接
        $connection->disconnect();
    }
}
