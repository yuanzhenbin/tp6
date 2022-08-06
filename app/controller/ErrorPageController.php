<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class ErrorPageController extends BaseController
{
    public function error()
    {
        View::assign('code',input('code'));
        View::assign('message',input('message'));
        return View::fetch();
    }

    public function success()
    {
        View::assign('code',input('code'));
        View::assign('message',input('message'));
        return View::fetch();
    }
}