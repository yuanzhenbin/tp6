<?php
namespace app\model;

use think\Model;

class ModelTest extends Model
{
    //指定表名，如果模型名与表名一致则不需要指定
    protected $name = 'user';
    //指定主键，默认id，不是id的时候需要指定
    protected $pk = 'id';
    // 设置当前模型对应的完整数据表名称
//    protected $table = 'user';
    //设置当前模型的数据库连接  切库
    protected $connection = 'test';

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

    // 模型数据不区分大小写
//    protected $strict = false;

    public static function getOne($where)
    {
        $data = self::where($where)->find();
        return  $data;
    }
}