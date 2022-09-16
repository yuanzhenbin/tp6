<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\BaseController;
use think\facade\Db;

class A
{
    function index()
    {
        return $this;
    }

    function __construct()
    {
        echo "this is class A";
    }

    function __destruct()
    {
        echo PHP_EOL."销毁了A实例对象";
    }

    function __sleep()
    {
        echo PHP_EOL."A类的sleep";
    }

    function __get($a)
    {
        echo PHP_EOL."判断你正在读取一个不存在的值".$a;
    }

    function __set($k,$v)
    {
        echo PHP_EOL."判断你正在给一个不存在的变量赋值".$k.$v;
    }

    function __isset($a)
    {
        echo PHP_EOL."判断你正在判断一个不存在的值".$a;
    }

    function __call($a,$arr)
    {
        echo PHP_EOL."判断你正在调用一个不存在的方法".$a;
        #var_dump($arr);
    }

    function __toString()
    {
        return PHP_EOL."判断你把不是字符串的东西当字符串用了";
    }

    function __invoke()
    {
        echo PHP_EOL."把类当做方法调用了";
    }

    function __clone()
    {
        echo PHP_EOL."克隆了A类的实例化对象";
    }
}

class B extends A
{
    function __construct()
    {
        echo "this is class B";
        echo PHP_EOL;
        parent::__construct();
    }

    function __destruct()
    {
        echo PHP_EOL."销毁了B实例对象";
    }

    public function bb()
    {
        echo "bb";
    }
}

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
        echo "<br>";

        $arr = [1];
        xdebug_debug_zval('arr');
        echo "<br>";
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function magicFun(Request $request)
    {
        echo "<pre>";
        $a = new A();
        $a->aaa;
        $a->bbb = 222;
        isset($a->ccc);
        $a->ddd(1,23);
        echo $a->index();
        $a();
        $aa = clone($a);
#unset($a);
        echo PHP_EOL;
        $b = new B();
//        serialize($a);
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
