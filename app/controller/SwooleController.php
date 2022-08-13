<?php
namespace app\controller;

use app\BaseController;
use GatewayClient\Gateway;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class SwooleController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

}