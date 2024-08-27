<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Work;


class DocController extends Controller
{

    public function document()
    {
        $lang = config('app.locale');
        if(session()->has('locale')) {
            $lang = session('locale');            
        }
        return view('docs.'.$lang.'.document');
    }
       
    public function oferta()
    {
        $lang = config('app.locale');
        if(session()->has('locale')) {
            $lang = session('locale');            
        }
        return view('docs.'.$lang.'.oferta');
    }
    
    public function policy()
    {
        $lang = config('app.locale');
        if(session()->has('locale')) {
            $lang = session('locale');            
        }
        return view('docs.'.$lang.'.policy');
    }

    public function instruction()
    {
        $lang = config('app.locale');
        if(session()->has('locale')) {
            $lang = session('locale');            
        }
        return view('docs.'.$lang.'.instruction');
    }
}
