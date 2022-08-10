<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onConnect:''\n";
        //把client_id返回到页面，页面使用client_id到框架里绑定uid，之后的推送由框架使用GatewayClient来完成
        Gateway::sendToClient($client_id, json_encode(array(
            'type'      => 'init',
            'client_id' => $client_id
        )));
    }
    
   /**
    * 当客户端发来消息时触发
    * todo 注意 GatewayClient的请求不会进这里 这个方法是针对js web_socket 写的
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";

       // 客户端传递的是json数据
       $message_data = json_decode($message, true);
       if(!$message_data)
       {
           return ;
       }

       // 根据类型执行不同的业务
       switch($message_data['type'])
       {
           // 客户端回应服务端的心跳
           case 'pong':
               return;
           // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
           case 'login':
               // 判断是否有房间号
               if(!isset($message_data['room_id']))
               {
                   throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
               }

               // 把房间号昵称放到session中
               $room_id = $message_data['room_id'];
               $uname = htmlspecialchars($message_data['uname']);
               $_SESSION['room_id'] = $room_id;
               $_SESSION['uname'] = $uname;
               $_SESSION['uid'] = $client_id;

               // 获取房间内所有用户列表
               $clients_list = Gateway::getClientSessionsByGroup($room_id);
               $user_list = [];
               foreach($clients_list as $tmp_client_id=>$item)
               {
                   $clients_list[$tmp_client_id] = $item['uname'];
                   $user_list[$item['uid']] = $item['uname'];
               }
               $clients_list[$client_id] = $uname;
               $user_list[$client_id] = $uname;

               //转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx}
               $new_message = array(
                   'type'=>$message_data['type'],
                   'uid'=>$client_id,
                   'uname'=>htmlspecialchars($uname),
                   'client_list'=>$clients_list,
                   'user_list'=>$user_list,
                   'time'=>date('Y-m-d H:i:s')
               );
               Gateway::joinGroup($client_id, $room_id);
               Gateway::sendToGroup($room_id, json_encode($new_message));
               return;
           // 客户端发言 message: {type:say, to_uid:xx, content:xx}
           case 'say':
               // 非法请求
               if(!isset($_SESSION['room_id']))
               {
                   throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
               }
               $room_id = $_SESSION['room_id'];
               $uname = $_SESSION['uname'];

               // 私聊
               if($message_data['to_uid'] != 'all')
               {
                   $new_message = array(
                       'type'=>'say',
                       'from_uid'=>$client_id,
                       'from_uname' =>$uname,
                       'to_uid'=>$message_data['to_uid'],
                       'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                       'time'=>date('Y-m-d H:i:s'),
                   );
                   Gateway::sendToClient($message_data['to_uid'], json_encode($new_message));
                   $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_uname'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                   return Gateway::sendToCurrentClient(json_encode($new_message));
               }

               $new_message = array(
                   'type'=>'say',
                   'from_uid'=>$client_id,
                   'from_uname' =>$uname,
                   'to_uid'=>'all',
                   'content'=>nl2br(htmlspecialchars($message_data['content'])),
                   'time'=>date('Y-m-d H:i:s'),
               );
               return Gateway::sendToGroup($room_id ,json_encode($new_message));
       }
   }

   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";

       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $new_message = array('type'=>'logout', 'from_uid'=>$client_id, 'from_uname'=>$_SESSION['uname'], 'uid'=>$_SESSION['uid'], 'time'=>date('Y-m-d H:i:s'));
           Gateway::sendToGroup($room_id, json_encode($new_message));
       }
   }
}
