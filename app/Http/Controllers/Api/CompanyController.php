<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{

    public function index(Request $request)
    {

      $limit = isset($request->limit) ? $request->limit : 5;
      $offset = isset($request->offset) ? $request->offset : 0;
      $search = isset($request->search) ? $request->search : '';

      if(!empty($search)){
        $companies = User::where('is_company', 1)
                        ->where(function ($q) use ($search) {
                            $q->where(function ($qq) use ($search) {
                                $qq->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('phone', 'like', '%' . $search . '%');
                            });
                        })
                        ->get();
      } else {
        $companies = User::where('is_company',1)->skip($offset)->take($limit)->get();
      }

    	
    	foreach ($companies as $company) {
		       $logo = null;
           if (!empty($company->logo)) {
                $logo = User::get_logo($company->logo);
                $company->logo = $logo;
           }  

            $company->counter_1 = ($company->counter_1) ?? 0;
            $company->counter_2 = ($company->counter_2) ?? 0;
            $company->counter_3 = ($company->counter_3) ?? 0;
            $company->counter_4 = ($company->counter_4) ?? 0;
            $company->counter_5 = ($company->counter_5) ?? 0;

    	}
        return response()->json($companies->toArray());
    }

    public function show($companyId) {
        $companyId = (int) $companyId;
        $user = User::find($companyId);
        if (!empty($user)) {

            if (!empty($user->logo)) {
             $logo = User::get_logo($user->logo);
             if (!empty($logo)) {
                $company_logo = $logo;
                $user->logo = $company_logo;
                }
            }

            $user->balance = intval($user->balance);

            $user->counter_1 = ($user->counter_1) ?? 0;
            $user->counter_2 = ($user->counter_2) ?? 0;
            $user->counter_3 = ($user->counter_3) ?? 0;
            $user->counter_4 = ($user->counter_4) ?? 0;
            $user->counter_5 = ($user->counter_5) ?? 0;

            return response()->json($user->toArray());  
        }
    }

    static function countCompanyUserInfo($company_id) {   

      $company_user = User::find($company_id);
      if (!empty($company_user)) {

          $counter_1 = Work::where('status', 1)->where('user_id', $company_id)->count();
          $company_user->counter_1 = $counter_1; 

          $counter_2 = Work::where('status', 2)->where('user_id', $company_id)->count();
          $company_user->counter_2 = $counter_2; 

          $counter_3 = Work::where('status', 3)->where('user_id', $company_id)->count();
          $company_user->counter_3 = $counter_3; 

          $counter_4 = Work::where('pay_confirm', 0)->where('user_id', $company_id)->count();
          $company_user->counter_4 = $counter_4; 

          $counter_5 = Work::where('user_id', $company_id)->count();
          $company_user->counter_5 = $counter_5; 

          $company_user->save();
      
      }
    }


}
