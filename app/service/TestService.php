<?php
declare (strict_types = 1);

namespace app\service;

use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;
use think\Service;
use app\service\MyService;

class TestService extends Service
{
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
        $this->app->bind('my_service', MyService::class);
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        MyService::setNum(123);
    }

    public static function test($id)
    {
        $data = Db::name('user')->where('id',$id)->find();
        return $data;
    }
}
