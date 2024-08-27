<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Hash;
use Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AdminAuthController extends Controller
{
      
    public function login()
    {
       return view('admin.login');  
    }

    public function post_login(Request $request)
    {
        $login = trim($request->get("login"));
        $password = trim($request->get("password"));
        $admin_user = User::find(1);

        if (!empty($admin_user)) {
            $admin_login = $admin_user->name;
            $admin_password = $admin_user->password;
            $admin_cookie = $admin_user->api_token;
            if ($login == $admin_login && Hash::check($password, $admin_password)) {
               $cookie = Cookie::make('admin_login', $admin_cookie, 60 * 8);     
               return redirect()->intended('admin/home')->withSuccess('Signed in')->cookie($cookie);
            }
        }
        $cookie = Cookie::make('admin_login', '');

        return redirect("admin-login")->withSuccess('Login details are not valid')->cookie($cookie);
    }

    public function logout() 
    {
      $cookie = Cookie::make('admin_login', '');      
      return redirect('admin-login')->cookie($cookie);
    }

}