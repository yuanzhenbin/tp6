<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;
use app\model\User;

class LoginController extends BaseController
{
    public function index()
    {
        if(session('uaccount')) {
            return redirect(url('Admin/index'));
        } else {
            return View::fetch();
        }
    }

    //登录
    public function login()
    {
        $account = input('account');
        $password = input('password');
        $verification = input('verification');
        //验证码非必填
        if ($verification && strtolower($verification) != session("verification")) {
            return_ajax([session("verification")],0,'验证码错误！');
        }
        if (!$account) {
            return_ajax([],0,'账号或密码错误！');
        }

        $uinfo = Db::name('user')->where('account',$account)->find();
//        var_dump(md5($password),$uinfo['password']);die;
        if(!$uinfo) {
            return_ajax([],0,'账号或密码错误！');
        }

        //hash_equals防时序攻击
        if ((!$uinfo['password'] && !$password) || hash_equals($uinfo['password'],md5($password.$uinfo['salt']))) {
            session('uid',$uinfo['id']);
            session('uname',$uinfo['name']);
            session('uaccount',$uinfo['account']);
            $salt = rand(10000,99999);
            $password_salt = md5($password.$salt);

            Db::startTrans();
            try{
                //保证密码和盐的一致性
                Db::name('user')->where('account',$account)->update(['password'=>$password_salt,'salt'=>$salt]);
                Db::commit();
            } catch (\Throwable $e){
                Db::rollback();
                Db::name('log')->insert(['content'=>'自动更新密码失败|'.$e->getMessage(),'create_time'=>time()]);
            }

            echo json_encode(['data'=>[],'code'=>200,'message'=>'登录成功']);
        } else {
            echo json_encode(['data'=>[],'code'=>0,'message'=>'账号或密码错误！']);
        }
//        设置session之后不能使用die或exit,否则设置不成功 ？？
//        return_ajax([]);
    }

    //注册页面
    public function registerView()
    {
        return View::fetch('register');
    }

    //注册
    public function register()
    {
        $account = input('account');
        $password = input('password');
        $name = input('name','');
        $phone = input('phone','');
        $email = input('email','');
        $sex = input('sex','');
        if (!$account) {
            return_ajax([], 0, '账号必填！');
        }
        if ($phone && !is_mobile($phone)) {
            return_ajax([], 0, '电话号码不正确！');
        }
        if ($email && !is_email($email)) {
            return_ajax([], 0, '邮箱不正确！');
        }

        $check = Db::name('user')->where('account',$account)->find();
        if ($check) {
            return_ajax([],0,'账号已存在！');
        }
        $add_data = [];
        $add_data['account'] = $account;
        $add_data['name'] = $name?$name:$account;
        $add_data['phone'] = $phone;
        $add_data['email'] = $email;
        $add_data['sex'] = $sex;
        $add_data['status'] = 1;
        $add_data['create_time'] = time();
        if ($password) {
            $salt = rand(10000,99999);
            $password_salt = md5($password.$salt);
            $add_data['password'] = $password_salt;
            $add_data['salt'] = $salt;
        }
//        var_dump($add_data['phone']);die;

        $user = new User();
        $ret = $user->save($add_data);

        if ($ret !== false) {
            return_ajax([$ret],200,'注册成功');
        } else {
            return_ajax([],0,'注册失败');
        }
    }

    public function verificationCode()
    {
        //1.创建黑色画布
        $image = imagecreatetruecolor(100, 30);

        //2.为画布定义(背景)颜色
        $bgcolor = imagecolorallocate($image, 255, 255, 255);

        //3.填充颜色
        imagefill($image, 0, 0, $bgcolor);

        // 4.设置验证码内容
        //4.1 定义验证码的内容 去掉了l和I、0
        $content = "ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789";

        //4.1 创建一个变量存储产生的验证码数据，便于用户提交核对
        $verification = "";

        for ($i = 0; $i < 4; $i++) {
            // 字体大小
            $fontsize = 10;
            // 字体颜色
            $fontcolor = imagecolorallocate($image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
            // 设置字体内容
            $fontcontent = substr($content, mt_rand(0, strlen($content)), 1);
            $verification .= $fontcontent;
            // 显示的坐标
            $x = ($i * 100 / 4) + mt_rand(5, 10);
            $y = mt_rand(5, 10);
            // 填充内容到画布中
            imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
        }

        session("verification",strtolower($verification));

        //4.3 设置背景干扰元素
        for ($$i = 0; $i < 200; $i++) {
            $pointcolor = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
            imagesetpixel($image, mt_rand(1, 99), mt_rand(1, 29), $pointcolor);
        }

        //4.4 设置干扰线
        for ($i = 0; $i < 3; $i++) {
            $linecolor = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
            imageline($image, mt_rand(1, 99), mt_rand(1, 29), mt_rand(1, 99), mt_rand(1, 29), $linecolor);
        }

        //5.向浏览器输出图片头信息
        header('content-type:image/png');

        //6.输出图片到浏览器
        imagepng($image);
    }

    //退出
    public function logout()
    {
        session(null);
        echo json_encode(['data'=>[],'code'=>200,'message'=>'退出成功']);
    }
}
