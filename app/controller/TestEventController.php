<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use app\service\MyService;

class TestEventController extends BaseController
{
    public function index()
    {
        event('EventTest.index');
    }
}
