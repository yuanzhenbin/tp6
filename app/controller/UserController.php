<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;
use app\model\User;
use app\validate\UserValidate;
use app\service\TestService;

class UserController extends BaseController
{
    public function index()
    {
        //$this->request->param() 等同于 Request::param() 因为BaseController里已经设置
        //也可以使用助手函数 request()->param() 为了简化调用，系统还提供了request助手函数，可以在任何需要的时候直接调用当前请求对象

        $page = input('page',1);
        $limit = input('limit',10);
        $first_row = (($page - 1) * $limit);
        $r_method = Request::method();
        if ($r_method == 'GET') {
            $data = Db::name('user')->where([['id','>',0]])->order('create_time desc')->limit($first_row, $limit)->select()->toArray();
            $sex_list = [0=>'未知', 1=>'男', 2=>'女'];
            $status_list = [0=>'未知', 1=>'正常', 2=>'删除'];
            foreach ($data as &$v) {
                $v['sex_show'] = isset($sex_list[$v['sex']])?$sex_list[$v['sex']]:'未知';
                $v['status_show'] = isset($status_list[$v['status']])?$status_list[$v['status']]:'未知';
            }

            View::assign('data',$data);
            return View::fetch();
        } else if ($r_method == 'POST') {
            $where = [];
            $where[] = ['id','>',0];
            $count = Db::name('user')->where($where)->count();
            $data = Db::name('user')->where($where)->limit($first_row, $limit)->order('id asc')->select()->toArray();
            $sex_list = [0=>'未知', 1=>'男', 2=>'女'];
            $status_list = [0=>'未知', 1=>'正常', 2=>'删除'];
            foreach ($data as &$v) {
                $v['sex_show'] = isset($sex_list[$v['sex']])?$sex_list[$v['sex']]:'未知';
                $v['status_show'] = isset($status_list[$v['status']])?$status_list[$v['status']]:'未知';
            }
            return_ajax($data,0,'',$count);
        } else {
            hello_php();
        }
    }

    public function addUser()
    {
        $add_data = [];
        $add_data['name'] = request()->param('name','');
        $add_data['phone'] = request()->param('phone','');
        $add_data['email'] = request()->param('email','');
        $add_data['sex'] = Request::param('sex',0);
        $add_data['status'] = Request::param('status',1);
        $add_data['create_time'] = time();

        //都不返回id 返回行数
//        $ret = Db::name('user')->save($add_data);
//        $ret = Db::name('user')->insert($add_data);
        //返回id
//        $ret = Db::name('user')->insertGetId($add_data);
        //返回添加的值 {name: "c2", phone: "13452", sex: "2", status: "1", create_time: "2022-07-18 18:49:51",…}
//        $ret = User::create($add_data);
        //返回行数
//        $ret = User::insert($add_data);
        //返回布尔值
        $user = new User();
        $ret = $user->save($add_data);

        if ($ret !== false) {
            return_ajax([$ret],200,'添加成功');
        } else {
            return_ajax([$ret],0,'添加失败');
        }
    }

    public function editUser()
    {
        $id = Request::param('id');
        $name = Request::param('name','');
        $phone = Request::param('phone','');
        $email = Request::param('email','');
        $sex = Request::param('sex',0);
        $status = Request::param('status',1);
        if(!$id) {
            return_ajax([],0,'缺少参数');
        }
        $ret = Db::name('user')
            ->where('id',$id)
            ->update(['name' => $name,'phone' => $phone,'email' => $email,'sex' => $sex,'status' => $status]);

        if ($ret !== false) {
            return_ajax([],200,'修改成功');
        } else {
            return_ajax([],0,'修改失败');
        }
    }

    public function delUser()
    {
        $id = input('id');
        //update效率比save高
        $ret = Db::name('user')->where('id',$id)->update(['status' => 2]);
//        $ret = Db::name('user')->where('id',$id)->save(['status' => 2]);
        if ($ret !== false) {
            return_ajax([],200,'删除成功');
        } else {
            return_ajax([],0,'删除失败');
        }
    }

    public function getInfo()
    {
        $id = Request::param('id');
        //tp5、tp6的where条件，要么不使用数组，要么必须是二维数组，如果查询条件是=，则=可以省略
        $info = Db::name('user')->where([['id','=',$id]])->find();
        if ($info) {
            return_ajax($info);
        } else {
            return_ajax([],0);
        }
    }

    public function admin()
    {
        //重定向
        return redirect(url('Admin/index'));
    }

    //验证器测试
    public function check()
    {
        $data = request()->param();
//        var_dump($data);die;
        try {
            validate(UserValidate::class)->check($data);
            return_ajax([],200,'验证通过');
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return_ajax([],0,$e->getError());
        }
    }

    public function serviceTest()
    {
        $id = 1;
        $data = TestService::test($id);
        var_dump($data);
    }
}
