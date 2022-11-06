<?php
namespace app\model;

use think\Model;

class User extends Model
{
    // 设置字段信息 模型的数据字段和对应数据表的字段是对应的，默认会自动获取（包括字段类型），但自动获取会导致增加一次查询，因此你可以在模型中明确定义字段信息避免多一次查询的开销。
    protected $schema = [
        'id'  => 'int',
        'name' => 'string',
        'phone'  => 'string',
        'email' => 'string',
        'status' => 'tinyint',
        'sex' => 'tinyint',
        'department_id' => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
        'account' => 'string',
        'password' => 'string',
        'salt' => 'string',
    ];

    //添加用户
    public function addUser($data)
    {
        $add_data = [];
        $add_data['account'] = $data['account'];
        $add_data['name'] = $data['name']?$data['name']:$data['account'];
        $add_data['phone'] = $data['phone'];
        $add_data['email'] = $data['email'];
        $add_data['sex'] = $data['sex'];
        $add_data['status'] = $data['status'];
        $add_data['create_time'] = time();
        if ($data['password']) {
            $salt = rand(10000,99999);
            $password_salt = md5($data['password'].$salt);
            $add_data['password'] = $password_salt;
            $add_data['salt'] = $salt;
        }

        return $this->save($add_data);
    }
}