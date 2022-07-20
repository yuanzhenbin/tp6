<?php
declare (strict_types = 1);

namespace app\middleware;

class Check2
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
        //后置中间件，这时已经执行了控制器的内容
        $response = $next($request);

        echo '当前使用'.$request->method().'方式请求'.$request->controller().'控制器<br/>';

        return $response;
    }
}
