<?php

use think\facade\Route;

//这个路由会覆盖下面带参数的路由
//Route::get('routetest', function () {
//    return 'hello,ThinkPHP6! --test';
//});

//rule未设置请求方式时,带参数则覆盖get，不带参数则覆盖post
//Route::rule('routetest/id/', function () {
//    return 'rule test/id id='.input('id');
//});
//Route::rule('routetest/id/:id', function ($id) {
//    return 'rule test/id id='.$id;
//});


//这两个作用是一样的
Route::post('routetest/id/', function () {
    return 'post route test/id='.input('id');
});
Route::rule('routetest/id/', function () {
    return 'rule post route test/id='.input('id');
}, 'POST');

//这个和下面两个是不同的
Route::get('routetest/id/', function () {
    return 'rule get route test/id';
});
//这两个作用是一样的
Route::get('routetest/id/:id', 'RouteTest/getId');
Route::rule('routetest/id/:id', function ($id) {
    return 'rule get route test/id id='.$id;
}, 'GET');


//跳转到指定 控制器/方法
Route::get('routetest/name/:name', 'RouteTest/getName');
Route::post('routetest/name', 'RouteTest/postName');

//路由中间件
Route::rule('middleware/:name',function () {
    return '路由中间件测试';
})
    ->middleware([\app\middleware\Check::class,\app\middleware\Check2::class]);
