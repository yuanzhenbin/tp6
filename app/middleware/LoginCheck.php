<?php
declare (strict_types = 1);

namespace app\middleware;

class LoginCheck
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
        $uid = session('uaccount');
        $response = $next($request);
        if (!$uid && $request->controller() != 'Login') {
            return redirect((string)url('Login/index'));
        }
        return $response;
    }
}
