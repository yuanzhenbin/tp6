<?php
namespace app\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:50',
        'email' =>  'email|max:50',
        'phone' =>  'number|require|max:11',
    ];

    protected $message  =   [
        'name.require'  => '姓名不能为空',
        'name.max'      => '姓名最多不能超过50个字符',
        'email.email'   => '邮箱格式不正确',
        'email.max'     => '邮箱最多不能超过50个字符',
        'phone.number'  => '电话格式不正确',
        'phone.max'     => '电话格式不正确',
        'phone.require' => '电话不能为空',
    ];
}