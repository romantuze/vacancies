<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Hash;
use Illuminate\Support\Str;
use Session;
use App\Models\User;

class CompanyAuthController extends Controller
{

    public function register(Request $request)
    {  
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required','string', 'min:10', 'max:13'],
            'logo' => ['string', 'max:255'],
        ]);           
        $data = $request->all();
        $new_user = $this->create($data);
        if(!empty($new_user)) {
            Auth::login($new_user);
        }
        return redirect("home")->withSuccess(__('text.YouHaveSignedIn'));     
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => strtolower($data['email']),
        'phone' => isset($data['phone']) ? $data['phone'] : NULL,
        'password' => Hash::make($data['password']),
        'logo' => NULL,
        'balance' => 0,
        'is_company' => 1,
        /*'is_candidate' => 0,*/
        'is_admin' => 0,
        'api_token' => Str::random(60),
      ]);
    }    

    public function login() {      
        return view('company.login');
    }
    public function register_page() {
        $google_recaptcha = config('app.google_recaptcha');
        return view('company.register',[
            'google_recaptcha' => $google_recaptcha,
        ]);
    }
}