<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use app\Model\ModelTest;
use app\service\MyService;

class ServiceTestController extends BaseController
{
    public function index(MyService $my_service)
    {
        $my_service::getNum();
    }
}
