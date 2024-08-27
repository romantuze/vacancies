<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin_login =  $request->hasCookie('admin_login');
        if (!empty($admin_login)) {
            $admin_login_value = $request->cookie('admin_login');
            //$admin_cookie = config('app.admin_cookie');
            $admin_user = User::find(1);
            if (!empty($admin_user)) {
                $admin_cookie = $admin_user->api_token;
                if ($admin_login_value === $admin_cookie) {
                    return $next($request);   
                }    
            }
        }
       return new RedirectResponse(url('admin-login'));        
    }
}
