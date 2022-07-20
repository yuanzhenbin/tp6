<?php
namespace app\controller;

use app\BaseController;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;
use app\model\User;
use app\validate\UserValidate;
use app\service\TestService;

class RedisTestController extends BaseController
{
    public function index()
    {
        Cache::store('redis')->set('name','redistest',5);
        var_dump(0,Cache::store('redis')->get('name'));
        sleep(5);
        var_dump(5,Cache::store('redis')->get('name'));
    }
}
