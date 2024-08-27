<?php

namespace App\Http\Controllers;

use App\Repositories\WorksRepository;
use Illuminate\Http\Request;
use App\Models\User;

class IndexController extends Controller
{

    public function index()
    {
        $user = Auth::user();   
        $app_lang = config('app.locale');   
        if (!empty($user) && !empty($user->lang) && ($user->lang !== $app_lang)) {
            app()->setLocale($user->lang);
        }
       return view('index');
    }

    public function test()
    {  
        return view('test');
    }

}
