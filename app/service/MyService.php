<?php
namespace app\service;

use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;

class MyService
{
    protected static $num;

    public static function setNum($num)
    {
        self::$num = $num;
    }

    public static function getNum()
    {
        var_dump('因为在系统服务中已经注册过了，所以一获取就是'.self::$num);
    }
}