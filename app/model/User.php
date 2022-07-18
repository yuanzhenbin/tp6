<?php
namespace app\model;

use think\Model;

class User extends Model
{
    // 设置字段信息 模型的数据字段和对应数据表的字段是对应的，默认会自动获取（包括字段类型），但自动获取会导致增加一次查询，因此你可以在模型中明确定义字段信息避免多一次查询的开销。
    protected $schema = [
        'id'          => 'int',
        'name'        => 'string',
        'phone'        => 'int',
        'status'      => 'tinyint',
        'sex'       => 'tinyint',
        'department_id' => 'int',
        'create_time' => 'int',
        'update_time' => 'int',
    ];
}