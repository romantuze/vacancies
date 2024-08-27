<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CompanyController;
use App\Models\Work;
use App\Models\Questionaire;
use App\Models\TypeContract;
use App\Models\Employment;
use App\Models\Education;
use App\Models\TypeEducation;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Car;
use App\Models\CarLicence;
use App\Models\Specialization;
use App\Models\City;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Cookie;


class WorkController extends Controller
{
    public function create()
    {
        $current_user = Auth::user();
        if (isset($current_user)) {
          $is_admin = false;
          $is_company = false;
          $current_user_name = $current_user->name;
          $user_id = $current_user->id;
          $company = $current_user->is_company;
          $admin = $current_user->is_admin;
          $logo = User::get_logo($current_user->logo);

          $lang = config('app.locale');
          $user = Auth::user();        
          if (!empty($user) && !empty($user->lang)) {
            $lang = $user->lang;
          }
         
          if ($user_id > 0 && (($company === 1))) {
           return view('work.create',[       
               'name' => $current_user_name,
               'logo' => $logo,
               'user_id' => $user_id,
               'lang' => $lang,
           ]);
         }
        }
        return redirect("home")->withSuccess(__('text.YouHaveSignedIn'));
    }


    public function edit($workId)
    {
      $workId = (int) $workId;
      $current_user = Auth::user();
      if (isset($current_user)) {
      $is_admin = false;
      $is_company = false;
      $current_user_name = $current_user->name;
      $user_id = $current_user->id;
      $company = $current_user->is_company;
      $admin = $current_user->is_admin;
      $logo = User::get_logo($current_user->logo);  

      $lang = config('app.locale');
      $user = User::find(1);        
      if (!empty($user) && !empty($user->lang)) {
        $lang = $user->lang;
      }

      if ($user_id > 0 && (($company === 1))) {
      return view('work.edit',[       
      'name' => $current_user_name,
      'logo' => $logo,
      'user_id' => $user_id,
      'work_id' => $workId,
      'lang' => $lang,
      ]);
      }
      }
      return redirect("home")->withSuccess(__('text.YouHaveSignedIn'));
    }

    public function template($workId) {
        $workId = (int) $workId;
        $current_user = Auth::user();
        if (isset($current_user)) {
        $is_admin = false;
        $is_company = false;
        $current_user_name = $current_user->name;
        $user_id = $current_user->id;
        $company = $current_user->is_company;
        $admin = $current_user->is_admin;
        $logo = User::get_logo($current_user->logo);   

        $lang = config('app.locale');
        $user = User::find(1);        
        if (!empty($user) && !empty($user->lang)) {
          $lang = $user->lang;
        }

        if ($user_id > 0 && (($company === 1))) {
        return view('work.template',[       
        'name' => $current_user_name,
        'logo' => $logo,
        'user_id' => $user_id,
        'work_id' => $workId,
        'lang' => $lang,
        ]);
        }
        }
        return redirect("home")->withSuccess(__('text.YouHaveSignedIn'));
    }


    public function shop($slug)
    {
       $current_user = Auth::user();
       if (isset($current_user)) {
        $userId = $current_user->id;       
        $company = $current_user->is_company;
        $work = Work::where('slug',$slug)->first();

        $lang = config('app.locale');
        $user = User::find(1);        
        if (!empty($user) && !empty($user->lang)) {
          $lang = $user->lang;
        }

        if (!empty($work)) {
            $workId = $work->id;
            if ($userId > 0 && (($company === 1))) {
              return view('company.shop.index',[
                'user_id' => $userId,
                'work_id' => $workId,  
                'lang' => $lang,
            ]);
          }           
        } 
      } 
      return redirect("home")->withSuccess(__('text.YouHaveSignedIn'));  
    }

    public function destroy($itemId)
    {
        $questionaires = Questionaire::where('work_id', $itemId)->get()->toArray();
        if (!empty($questionaires)) {
          return "В вакансии остались неудаленные опросники";   
        }
        if ($itemId > 0) {
          $work = Work::find($itemId);
          $companyId = $work->user_id;
          CompanyController::countCompanyUserInfo($companyId);
          $work = Work::destroy($itemId);
          return "Вакансия удалена";   
        }        
       abort(404);
       return false;
    }
}
