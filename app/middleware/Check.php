<?php
declare (strict_types = 1);

namespace app\middleware;

class Check
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->param('token');
        if (!$token) {
            echo '非法访问，缺少令牌<br/>';
//            return redirect((string)url('admin/index',['token'=>'test']));
        }
        return $next($request);
    }
}
