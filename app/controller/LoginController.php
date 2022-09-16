<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

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

    //注册
    public function register()
    {

    }

    //退出
    public function logout()
    {
        session(null);
        echo json_encode(['data'=>[],'code'=>200,'message'=>'退出成功']);
    }
}
