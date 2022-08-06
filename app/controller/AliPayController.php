<?php
namespace app\controller;

use alipay\AopClient;
use alipay\request\AlipayTradePagePayRequest;
use alipay\request\AlipayTradeQueryRequest;
use alipay\request\AlipayTradeRefundRequest;
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
        $number = input('number');
        //继续支付
        if ($number) {
            $info = Db::name('alipay')->where('number',$number)->find();
            if (!$info) {
                return return_error('error',0,'订单不存在！');
            }
            if ($info['status'] != 1) {
                return return_error('error',0,'已完成支付！');
            }
            $name = $info['name'];
            $amount = $info['amount'];
            $describe = $info['describe'];
        } else {
            //新的支付
            $number = $this->getPayNumber();
            $name = input('name');
            $amount = input('amount');
            if (!$name || !is_numeric($amount)) {
                return return_error('error',0,'缺少参数!');
            }
            $describe = input('describe');

            Db::name('alipay')->insert([
                'number' => $number,
                'name' => $name,
                'amount' => $amount,
                'describe' => $describe,
                'status' => 1,
                'create_time' => time(),
            ]);
        }
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

    public function info()
    {
        $id = input('id');
        if (!$id) {
            return_ajax([],0,'缺少参数');
        }
        $info = Db::name('alipay')->where('id',$id)->find();

        $pay = new AopClient();
        $pay->gatewayUrl = config('protected.aliGatewayUrl');
        $pay->appId = config('protected.aliAppId');
        $pay->apiVersion = '1.0';
        $pay->postCharset='UTF-8';
        $pay->format='json';
        $pay->signType = 'RSA2';
        $pay->alipayrsaPublicKey = config('protected.alipayrsaPublicKey');
        $pay->rsaPrivateKey = config('protected.alipayrsaPrivateKey');

        $request = new AlipayTradeQueryRequest();
        $request->setBizContent(json_encode([
            'out_trade_no' => $info['number'],
            'trade_no' => $info['ali_number'],
        ]));
        $response= $pay->execute($request);
        $response = json_decode(json_encode($response),TRUE);
        $alipay_trade_query_response = $response['alipay_trade_query_response'];
        $sign = $response['sign'];
        if ($alipay_trade_query_response['code'] == 10000) {
            if(!$info['ali_number']) {
                $info['ali_number'] = $alipay_trade_query_response['trade_no'];
                Db::name('alipay')->where('number', $info['number'])->update(['ali_number' => $alipay_trade_query_response['trade_no']]);
            }
            $data = array_merge($info,$alipay_trade_query_response);
            return_ajax($data);
        } else {
            return_ajax([],0,'查询失败');
        }
    }

    public function refund()
    {
        $id = input('id');
        if (!$id) {
            return_ajax([],0,'缺少参数');
        }
        $info = Db::name('alipay')->where('id',$id)->find();
        if ($info['status'] != 2) {
            return_ajax([],0,'订单不能退款');
        }

        $pay = new AopClient();
        $pay->gatewayUrl = config('protected.aliGatewayUrl');
        $pay->appId = config('protected.aliAppId');
        $pay->apiVersion = '1.0';
        $pay->postCharset='UTF-8';
        $pay->format='json';
        $pay->signType = 'RSA2';
        $pay->alipayrsaPublicKey = config('protected.alipayrsaPublicKey');
        $pay->rsaPrivateKey = config('protected.alipayrsaPrivateKey');

        $request = new AlipayTradeRefundRequest();
        $request->setBizContent(json_encode([
            'out_trade_no' => $info['number'],
            'refund_amount' => $info['amount'],
            'refund_reason' => input('refund_reason',''),
        ]));
        $response= $pay->execute($request);
        $response = json_decode(json_encode($response),TRUE);
        $alipay_trade_refund_response = $response['alipay_trade_refund_response'];
        if ($alipay_trade_refund_response['code'] == 10000) {
            Db::name('alipay')->where('number', $info['number'])->update(['status'=>5,'refund_number'=>$info['number'], 'update_time'=>time()]);
            return_ajax([],200,'退款成功');
        } else {
            return_ajax([],0,'退款失败');
        }
    }

    public function notifyReturn()
    {
        //这里需要外网能访问，所以这个回调是调不通的
        data_log(2,json_encode(input()),'支付宝回调');
        return_ajax([],200,'success');
    }

    public function notify()
    {
        $number = input('out_trade_no');
        $ali_number = input('trade_no');
        $seller_id = input('seller_id');
        $info = Db::name('alipay')->where('number',$number)->find();
        if (!$info) {
            $info = [
                'number' => $number,
                'name' => '',
                'amount' => '',
                'describe' => '',
            ];
        } else {
            if ($info['status'] != 2) {
                Db::name('alipay')->where('number', $number)->update([
                    'status' => 2,
                    'update_time' => time(),
                    'ali_number' => $ali_number,
                    'seller_id' => $seller_id
                ]);
            }
        }

        View::assign('number',$info['number']);
        View::assign('name',$info['name']);
        View::assign('amount',$info['amount']);
        View::assign('describe',$info['describe']);
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

    public function order()
    {
        $r_method = Request::method();
        if ($r_method == 'GET') {
            return View::fetch();
        } else if ($r_method == 'POST') {
            $page = input('page',1);
            $limit = input('limit',10);
            $first_row = (($page - 1) * $limit);
            $search = input('search');
            $where = [];
            $where[] = ['id','>',0];
            if ($search) {
                $where[] = ['number|ali_number','=',$search];
            }
            $count = Db::name('alipay')->where($where)->count();
            $data = Db::name('alipay')
                ->where($where)
                ->limit($first_row,$limit)
                ->order('id descc')
                ->select()
                ->toArray();
            $status_list = [0=>'未设置', 1=>'未支付', 2=>'支付完成', 3=>'支付失败', 4=>'退款中', 5=>'退款成功'];
            foreach ($data as &$v){
                $v['status_show'] = $status_list[$v['status']];
            }

            return_ajax($data,0,'',$count);
        }
    }

    public function check($data)
    {
        $pay = new AopClient();
        $pay->alipayrsaPublicKey = config('protected.alipayrsaPublicKey');

        $res = $pay->rsaCheckV1($data,'','RSA2');//官方验签方法
        if ($res) {
            return true;
        }else{
            return false;
        }
    }
}
