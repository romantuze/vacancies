<?php

namespace App\Http\Controllers;

use App\Repositories\WorksRepository;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TranslateController extends Controller
{

    public function translate()
    {
        $tr = new GoogleTranslate();
        $tr->setSource('ru');
        $tr->setTarget('en');
    }

    public function set_locale($locale)
    {
        if (! in_array($locale, ['en', 'ru', 'es'])) {
            abort(400);
        }
        \App::setLocale($locale);
        Session::put('locale', $locale);
        $user = Auth::user();
        if (!empty($user)) {
            $user->lang = $locale;
            $user->save();
        }        
        return back();
    }


    public function assets_lang()
    {
        $lang = config('app.locale');
        $user = Auth::user();      
        if (!empty($user) && !empty($user->lang)) {
            $lang = $user->lang;
        }
        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }
        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings) . ';');
        exit();
    }

}
