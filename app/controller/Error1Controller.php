<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;

class Error1Controller extends BaseController
{
    public function __call($fun_name, $param)
    {
        return "非法访问！";
    }

//    public function index()
//    {
//        echo "访问找不到的控制器时会跳转到这里，如果访问的方法在这里没有定义就会跳转到__call方法";
//    }
}