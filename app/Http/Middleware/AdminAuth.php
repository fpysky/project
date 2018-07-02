<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    /**
     * 系统后台登陆控制中间件
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $identity = session('identity');
        if(empty($identity)){
            return redirect('/admin/public/login');
        }
        return $next($request);
    }
}
