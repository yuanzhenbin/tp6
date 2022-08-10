<?php
namespace app\controller;

use app\BaseController;
use GatewayClient\Gateway;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class WorkerManController extends BaseController
{
    public function index()
    {
        $room_id = input('room_id',1);
        View::assign('room_id',$room_id);
        return View::fetch();
    }

    public function indexAnother()
    {
        $room_id = input('room_id',1);
        View::assign('room_id',$room_id);
        return View::fetch('worker_man/index_another');
    }

    //客户端连接到聊天室
    public function login()
    {
        $client_id = input('client_id');
        if (!$client_id) {
            return_ajax([],0,'网络错误，请刷新页面重试');
        }
        $room_id = input('room_id',1);
        $uid = session('uid');
        //记录房间id与client_id 方便后续使用
        session('chat_room_id',$room_id);
        session('chat_client_id',$client_id);

        Gateway::$registerAddress = '127.0.0.1:1236';
        //向Gateway中设置新用户的session Gateway那边的session和nginx这里的session不共享 使用这个方法才能设置
        Gateway::setSession($client_id,[
            'room_id' => $room_id,
            'uname' => session('uname'),
            'uid' => session('uid'),
        ]);
        //client_id与uid绑定 后续业务使用uid操作
        Gateway::bindUid($client_id, $uid);
        //加入到群组
        Gateway::joinGroup($client_id, $room_id);
        //获取房群组所有用户列表
        $clients_list = Gateway::getClientSessionsByGroup($room_id);
        foreach($clients_list as $tmp_client_id=>$item)
        {
            $clients_list[$tmp_client_id] = $item['uname'];
            $user_list[$item['uid']] = $item['uname'];
        }
        $clients_list[$client_id] = session('uname');
        $user_list[session('uid')] = session('uname');
        // 转播给当前群组的所有客户端，xx进入聊天室 更新所有人的在线用户列表
        $new_message = [
            'type' => 'login',
            'uid' => session('uid'),
            'uname' => session('uname'),
            'time' => date('Y-m-d H:i:s'),
            'client_list' => $clients_list,
            'user_list'=>$user_list,
        ];
        Gateway::sendToGroup($room_id, json_encode($new_message));
        //记录日志
        $log = session('uid').date('Y-m-d H:i:s').'进入房间'.$room_id;
        data_log(4,$log,'聊天');

        return_ajax([]);
    }

    //客户端发言
    public function send()
    {
        $type = input('type');
        if (!$type) {
            return false;
        }
        if ($type == 'pong') {
            //心跳检测
            return true;
        } else if ($type == 'say') {
            //客户端发言
            $message = input('message');//消息
            $room_id = input('room_id');//房间
            $to_uid = input('to_uid');//聊天目标
            if (!$room_id || !$to_uid) {
                return_ajax([], 0, '请选择聊天目标！');
            }
            $from_uname = session('uname');//发言人
            $from_uid = session('uid');//发言人

            Gateway::$registerAddress = '127.0.0.1:1236';
            //记录日志
            $log = $from_uid.'在房间['.$room_id.']向['.$to_uid.']发送消息:'.$message;
            data_log(4, $log, '聊天');
            if ($to_uid == 'all') {
                //群聊
                $new_message = [
                    'type' => 'say',
                    'from_uid' => $from_uid,
                    'from_uname' => $from_uname,
                    'to_uid' => 'all',
                    'to_uname' => '所有人',
                    'content' => nl2br(htmlspecialchars($message)),
                    'time' => date('Y-m-d H:i:s'),
                ];
                return Gateway::sendToGroup($room_id, json_encode($new_message));
            } else {
                //私聊
                $to_uname = Db::name('user')->where('id',$to_uid)->value('name');
                if (!$to_uname) {
                    return_ajax([], 0, '请选择正确的聊天目标！');
                }
                $new_message = [
                    'type' => 'say',
                    'from_uid' => $from_uid,
                    'from_uname' => $from_uname,
                    'to_uid' => $to_uid,
                    'to_uname' => $to_uname,
                    'content' => "<b>对你说: </b>" . nl2br(htmlspecialchars($message)),
                    'time' => date('Y-m-d H:i:s'),
                ];
                //对私聊对象发信息
                Gateway::sendToUid($to_uid, json_encode($new_message));
                $new_message['content'] = "<b>你对" . htmlspecialchars($to_uname) . "说: </b>" . nl2br(htmlspecialchars($message));
                //对自己也要发信息显示在聊天框
                return Gateway::sendToUid($from_uid, json_encode($new_message));
            }
        }

        return_ajax([]);
    }
}