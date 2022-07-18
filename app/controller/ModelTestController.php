<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use app\Model\ModelTest;

class ModelTestController extends BaseController
{
    public function getInfo()
    {
        $id = Request::param('id');
        //test
        $info1 = ModelTest::where('id','=',$id)->find();
        $info2 = ModelTest::getOne([['id','=',$id]]);
        //mysql
        $info3 = Db::name('user')->where('id','=',$id)->find();
        if ($info1) {
            return_ajax([1=>$info1,2=>$info2,3=>$info3]);
        } else {
            return_ajax([],0);
        }
    }

    public function delUser()
    {
        $id = input('id');
        $model_test = new ModelTest();
        $ret = $model_test::where('id',$id)->update(['status' => 2]);
        if ($ret !== false) {
            return_ajax([],200,'删除成功');
        } else {
            return_ajax([],0,'删除失败');
        }
    }
}
