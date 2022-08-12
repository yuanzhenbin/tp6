<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use app\Model\ModelTest;

class TestModelController extends BaseController
{
    //getinfo
    public function index()
    {
        $id = Request::param('id',1);
        //test 这两个默认返回对象
        $info1 = ModelTest::where('id','=',$id)->find()->toArray();
        $info2 = ModelTest::getOne([['id','=',$id]])->toArray();
        //mysql
        $info3 = Db::name('user')->where('id','=',$id)->find();
        if ($info1) {
            var_dump($info1,"<br>",$info2,"<br>",$info3);
            return_ajax([1=>$info1,2=>$info2,3=>$info3]);
        } else {
            var_dump([]);
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
