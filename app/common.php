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

function return_error($type = 'error', $code = 200, $message = "error", $data = []){
    if ('error' == strtolower($type)) {
        return redirect(url('ErrorPage/error',['code'=>$code,'message'=>$message,'data'=>$data]));
    } else {
        return redirect(url('ErrorPage/success',['code'=>$code,'message'=>$message,'data'=>$data]));
    }
}

function data_log(int $type, string $content, string $title = "", int $create_time = 0){
    think\facade\Db::name('log')->insert([
        'type' => $type,
        'content' => $content,
        'title' => $title,
        'create_time' => $create_time?:time()
    ]);
}

//判断11位电话号码
function is_mobile($mobile)
{
    return preg_match("/^1[3-9]\d{9}$/", $mobile) ? TRUE : FALSE;
}

//判断邮箱
function is_email($email)
{
    return preg_match("/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims", $email) ?  TRUE : FALSE;
}