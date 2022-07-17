<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\View;

class User extends BaseController
{
    public function index()
    {
//        $first = Db::table('user')->where('id','>',1)->find();
        $data = Db::table('user')->where([['id','>',0]])->select()->toArray();
        $sex_list = [0=>'未知', 1=>'男', 2=>'女'];
        $status_list = [0=>'未知', 1=>'正常', 2=>'删除'];
        foreach ($data as &$v) {
            $v['sex_show'] = isset($sex_list[$v['sex']])?$sex_list[$v['sex']]:'未知';
            $v['status_show'] = isset($status_list[$v['status']])?$status_list[$v['status']]:'未知';
        }

        View::assign('data',$data);
        return View::fetch();
    }
}
