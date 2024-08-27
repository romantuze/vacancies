<?php

namespace App\Http\Controllers;

use Auth;
use App\Mail\SendQuestionaire;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
       return view('admin.index');
    }

    public function selects()
    {
       return view('admin.selects.selects');
    }

    public function companies()
    {
        return view('admin.companies');
    }

    public function vacancies()
    {
       return view('admin.vacancies');
    }

    public function company($companyId)
    {
        $company_id = (int) $companyId;
        return view('admin.company',[
            'company_id' => $company_id,
        ]);
    }

    public function work($workId)
    {
       $work_id = (int) $workId;
       $user_id = 1;
        return view('admin.work',[
            'work_id' => $work_id,
            'user_id'=>$user_id,
        ]);
    }

    public function cities() 
    {
        $classifier_edit = config('app.classifier_edit');  
        if ($classifier_edit) return view('admin.selects.cities');
    }

    public function regions() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.regions');
    }

    public function educations() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.educations');
    }

    public function type_educations() 
    {
       $classifier_edit = config('app.classifier_edit');
       if ($classifier_edit)  return view('admin.selects.type_educations');
    }

    public function specializations() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.specializations');
    }

    public function skills() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.skills');
    }

    public function languages() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.languages');
    }

    public function cars() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.cars');
    }
    
    public function car_licences() 
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.car_licences');
    }

    public function language_levels()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.language_levels');
    }

    public function employments()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.employments');
    }

    public function degrees()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.degrees');
    }

    public function skill_levels()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.skill_levels');
    }

    public function type_contracts()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.type_contracts');
    }

    public function facilitations()
    {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.facilitations');
    }

   public function currencies()
   {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.currencies');
   }

   public function personals()
   {
        $classifier_edit = config('app.classifier_edit');
        if ($classifier_edit) return view('admin.selects.personals');
   }

}

