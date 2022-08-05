<?php
namespace MyTest;

class MyTestThree
{
    public $hi = 'php';

    public function hi(string $str = 'php')
    {
        echo "MyTestThree:hi ".$str."\n";
    }
}