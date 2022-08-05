<?php
namespace MyTest;

require_once 'MyTestThree.php';

class MyTextTwo
{
    public function hello(string $str = 'php')
    {
        echo "MyTestTwo:hello ".$str."\n";
    }

    public function hi(string $str = 'php')
    {
        $a = new MyTestThree();
        $a->hi();
    }
}