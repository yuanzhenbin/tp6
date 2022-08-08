<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class WorkerManController extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function success()
    {
        View::assign('code',input('code'));
        View::assign('message',input('message'));
        return View::fetch();
    }
}