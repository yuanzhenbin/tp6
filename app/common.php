<?php
// 应用公共文件

function hello_php(){
    echo "hello world! hello php!";
}

function return_ajax($data, $code = 200, $message = "success", $count = 0){
    exit(json_encode([
        "data" => $data,
        "code" => $code,
        "message" => $message,
        "count" => $count
    ]));
}

function data_log(int $type, string $content, string $title = "", int $create_time = 0){
    think\facade\Db::name('log')->insert([
        'type' => $type,
        'content' => $content,
        'title' => $title,
        'create_time' => $create_time?:time()
    ]);
}
