<?php
namespace app\controller;

use app\BaseController;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use think\exception\ValidateException;
use app\model\User;
use app\validate\UserValidate;
use app\service\TestService;

class GoodsController extends BaseController
{
    public $status_list = [0=>'未设置', 1=>'上架', 2=>'下架', 3=>'删除'];

    public function index()
    {
        $r_method = Request::method();
        if ($r_method == 'GET') {
            return View::fetch();
        } else if ($r_method == 'POST') {
            $page = input('page',1);
            $limit = input('limit',10);
            $first_row = (($page - 1) * $limit);
            $search = input('search');
            $where = [];
            $where[] = ['id','>',0];
            if ($search) {
                $where[] = ['name','like','%'.$search.'%'];
            }
            $count = Db::name('goods')->where($where)->count();
            $data = Db::name('goods')->where($where)->limit($first_row, $limit)->order('id asc')->select()->toArray();
            foreach ($data as &$v) {
                $v['status_show'] = isset($this->status_list[$v['status']])?$this->status_list[$v['status']]:'未知';
            }
            return_ajax($data,0,'',$count);
        }
    }

    public function addGoods()
    {
        $name = request()->param('name');
        if (!$name) {
            return_ajax([],0,'账号必填！');
        }
        $price = request()->param('price');
        if (!is_numeric($price)) {
            return_ajax([],0,'价格必须为数字！');
        }
        $check = Db::name('goods')->where('name',$name)->find();
        if ($check) {
            return_ajax([],0,'商品已存在！');
        }
        $add_data = [];
        $add_data['number'] = $this->get_number();
        $add_data['name'] = $name;
        $add_data['price'] = $price;
        $add_data['stock'] = request()->param('stock',0);
        $add_data['sales'] = Request::param('sales',0);
        $add_data['status'] = input('status',0);
        $add_data['create_time'] = time();

        $ret = Db::name('goods')->insertGetId($add_data);

        if ($ret) {
            return_ajax([$ret],200,'添加成功');
        } else {
            return_ajax([],0,'添加失败');
        }
    }

    public function editGoods()
    {
        $id = input('id');
        $name = input('name');
        $price = input('price');
        if(!$id || !$name) {
            return_ajax([],0,'缺少参数');
        }
        if (!is_numeric($price)) {
            return_ajax([],0,'价格必须为数字！');
        }
        $check = Db::name('goods')->where('name',$name)->find();
        if ($check) {
            return_ajax([],0,'商品已存在！');
        }
        $edit_data = [
            'name' => $name,
            'price' => $price,
            'stock' => input('stock',0),
            'sales' => input('sales',0),
            'status' => input('status',0),
            'update_time' => time(),
        ];

        $ret = Db::name('goods')->where('id',$id)->update($edit_data);

        if ($ret !== false) {
            return_ajax([],200,'修改成功');
        } else {
            return_ajax([],0,'修改失败');
        }
    }

    public function delGoods()
    {
        $id = input('id');
        $ret = Db::name('goods')->where('id',$id)->update(['status' => 3]);

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

    //生成商品编号
    public function get_number()
    {
        $redis = Cache::store('redis');
        $num = $redis->incr('goods_number');
        $number = 'TEST'.date('Ymd',time()).rand(100,999).$num;

        return $number;
    }

    //商品详情
    public function info()
    {
        $r_method = Request::method();
        if ($r_method == 'GET') {
            return View::fetch();
        } else if ($r_method == 'POST') {
            $id = input('id');
            if (!$id) {
                return_ajax([],0,'id必填');
            }
            $redis = Cache::store('redis');
            $info = $redis->get('goods_data_'.$id);
            if ($info) {
                $info['source'] = 'redis缓存';
            } else {
                $info = Db::name('goods')->where('id', $id)->find();
                $info['status_show'] = isset($this->status_list[$info['status']]) ? $this->status_list[$info['status']] : '未知';
                $info['source'] = '数据库';
                $info['data_time'] = date('Y-m-d H:i:s');

                $redis->set('goods_data_'.$id, $info, 10+rand(1,10));
            }

            $info['now_time'] = date('Y-m-d H:i:s');
            return_ajax($info,200,'查找成功');
        }
    }

    //从个人中心修改信息
    public function editInfo()
    {
        $id = session('uid');
        $name = input('name','');
        $phone = input('phone','');
        $email = input('email','');
        $sex = input('sex',0);
        $password = input('password',1);
        if(!$id) {
            return_ajax([],0,'缺少参数');
        }

        $save_data = [];
        $save_data['name'] = $name;
        $save_data['phone'] = $phone;
        $save_data['email'] = $email;
        $save_data['sex'] = $sex;
        $save_data['update_time'] = time();
        if ($password) {
            $salt = rand(10000,99999);
            $password_salt = md5($password.$salt);
            $save_data['password'] = $password_salt;
            $save_data['salt'] = $salt;
        }

        Db::startTrans();
        try {
            $ret = Db::name('user')->where('id', $id)->update($save_data);

            if ($ret !== false) {
                session('uname',$name);
                Db::commit();
                echo json_encode(['data'=>[],'code'=>200,'message'=>'保存成功']);
            } else {
                Db::rollback();
                return_ajax([], 0, '保存失败');
            }
        } catch (\Throwable $e) {
            Db::rollback();
            Db::name('log')->insert(['content'=>$e->getMessage(),'create_time'=>time()]);
            return_ajax([], 0, '保存失败');
        }
    }
}
