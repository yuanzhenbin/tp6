<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Admin extends BaseController
{
    public function index()
    {
        return View::fetch();
    }
}
