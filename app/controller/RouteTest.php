<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\View;

class RouteTest extends BaseController
{
    public function index()
    {
        echo "test";
    }

    public function getId($id)
    {
        echo "get control test/id id=".$id;
    }

    public function postId()
    {
        $id = Request::param('id');
        echo "post control test/id id=".$id;
    }

    public function getName()
    {
        //无法使用get方法获取路由变量，需要使用param
        $name = Request::param('name');
        echo "get control test/name name=".$name;
    }

    public function postName()
    {
        //var_dump(input());
        $name = Request::post('name');
        echo "post control test/name name=".$name;
    }
}