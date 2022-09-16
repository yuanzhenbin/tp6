<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\BaseController;
use think\facade\Db;

class TestPHPController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function gc()
    {
        $a = 1;
        xdebug_debug_zval('a');
        echo "<br>";
        $aa = "this is string";
        xdebug_debug_zval('aa');
        echo "<br>";
        $c = $b = $a;
        xdebug_debug_zval('a');
        echo "<br>";
        $cc = $bb = $aa;
        xdebug_debug_zval('aa');
        echo "<br>";
        echo "<br>";

        $d = &$a;
        xdebug_debug_zval('a');
        echo "<br>";
        unset($d);
        xdebug_debug_zval('a');
        echo "<br>";

        $dd = &$aa;
        xdebug_debug_zval('aa');
        echo "<br>";
        unset($aa);
        xdebug_debug_zval('dd');
        echo "<br>";
        echo "<br>";

        $bool = true;
        xdebug_debug_zval('bool');
        echo "<br>";
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
