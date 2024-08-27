<?php
namespace App\Http\Controllers;

use App\Repositories\WorksRepository;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class BaseController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorksRepository $worksRepository)
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $current_user = Auth::user();
        if (empty($current_user)) {
           return Redirect('login');
        }

       $is_company = false;
      
       $current_user_name = $current_user->name;
       $balance = intval($current_user->balance);
       $currency = $current_user->currency;
       $user_id = $current_user->id;

       $user = User::find(1);  
       $lang = 'ru';      
       if (!empty($user) && !empty($user->lang)) {
            $lang = $user->lang;
        }       

       $logo = User::get_logo($current_user->logo);

       if ($current_user->is_company===1) {
            $is_company = true;
        }

        if ($is_company) {

            $status_works = 2;
            $get_status = (int) $request->get('status');
            if ($get_status === 1) {
                $status_works = 1;
            } 
            if ($get_status === 2) {
                $status_works = 2;
            } 
            if ($get_status === 3) {
                $status_works = 3;
            } 
  
            return view('company.index',[            
                'is_company' => $is_company,             
                'name' => $current_user_name,
                'balance' => $balance,
                'currency' => $currency,
                'user_id' => $user_id,
                'logo' => $logo,
                'status_works' => $status_works,
                'lang' => $lang,
            ]);
        }    
    }

    public function test()
    {        
        /*
        $password = "";
        $pas = Hash::make($password);
        //$2y$10$nUoODyIVik9BKvhuy/kHDeorDqzgGYR0hD5tNcoPj20YFsJ68OSYC*/
    }

}
