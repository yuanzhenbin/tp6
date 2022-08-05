<?php
namespace app\controller;

use alipay\AopClient;
use alipay\request\AlipayTradePagePayRequest;
use alipay\request\AlipayTradeQueryRequest;
use app\BaseController;
use MyTest\MyTextTwo;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;


class AliPayController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function pay()
    {
        $pay = new AopClient();
        $pay->gatewayUrl = config('protected.aliGatewayUrl');
        $pay->appId = config('protected.aliAppId');
        $pay->apiVersion = '1.0';
        $pay->postCharset='UTF-8';
        $pay->format='json';
        $pay->signType = 'RSA2';
        // 开启页面信息输出
        $pay->debugInfo=true;
        $pay->alipayrsaPublicKey = config('protected.alipayrsaPublicKey');
        $pay->rsaPrivateKey = config('protected.alipayrsaPrivateKey');

        $notify_url = 'https://www.cnblogs.com/yuanshen/';
//        $return_url = 'https://www.cnblogs.com/yuanshen/';
//        $notify_url = 'http://my.tp6.com/index.php/AliPay/notifyReturn';
        $return_url = 'http://my.tp6.com/index.php/AliPay/notify';

        $name = input('name');
        $amount = input('amount');
        if (!$name || !is_numeric($amount)) {
            return_ajax([],0,'缺少参数');
        }
        $number = $this->getPayNumber();
        $describe = input('describe');

//        var_dump($describe,$name,$amount,$number);die;

        $request = new AlipayTradePagePayRequest();
        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($return_url);
        $request->setBizContent(json_encode([
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
            'body' => $describe?:'商品描述 可空',
            'subject' => $name,
            'total_amount' => $amount,
            'out_trade_no' => $number,
        ]));
        $response= $pay->pageExecute($request,"post");

        return $response;
    }

    public function refund()
    {

    }

    public function notifyReturn()
    {
        data_log(2,json_encode(input()),'支付宝回调');
        return_ajax([],200,'success');
    }

    public function notify()
    {
        View::assign('number',input('trade_no'));
        View::assign('name','111');
        View::assign('amount',input('total_amount'));
        View::assign('describe','111');
        return View::fetch();
    }

    public function getPayNumber()
    {
        $redis = Cache::store('redis');
        $pay_number = $redis->incr('pay_number');
        $number = date('YmdHi').rand(10000,99999).$pay_number;

        return $number;
    }

    public function test()
    {
        $a = new \MyTextOne();
        $a->hello();

        $b = new MyTextTwo();
        $b->hello();
        $b->hi();
    }

}
