<?php
namespace app\controller;

use app\BaseController;

class MiddleWareTestController extends BaseController
{
    //控制器中间件
    protected $middleware = [\app\middleware\Check::class,'check2'];

    public function index()
    {
        echo 'index';
    }

    public function add()
    {
        echo 'add';
    }

    public function del()
    {
        echo 'del';
    }
}
