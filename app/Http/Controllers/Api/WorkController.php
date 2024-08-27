<?php

namespace App\Http\Controllers\Api;
use App\Repositories\WorksRepository;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Questionaire;
use App\Models\GroupSpecialization;
use App\Models\Specialization;
use App\Models\TypeContract;
use App\Models\Employment;
use App\Models\Education;
use App\Models\TypeEducation;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Car;
use App\Models\CarLicence;
use App\Models\City;
use App\Models\Region;
use App\Models\User;
use App\Models\Personal;



use Illuminate\Support\Facades\Storage;
use Auth;

class WorkController extends Controller
{
    /**
     * @var WorksRepository
     */

    private $worksRepository;

    public function __construct(WorksRepository $worksRepository)
    {
        $this->worksRepository = $worksRepository;
    }

    public function index(Request $request)
    {
        if (isset($request->status_id)) {
            $status_id = (int) $request->status_id;
            $user_id = (int) $request->user_id;
            $works = Work::where(Work::FIELD_STATUS, $status_id)->where(Work::FIELD_USER_ID, $user_id)->get();
        }
        else if(isset($request->user_id)) {
            $user_id = (int) $request->user_id;
            $works = Work::where(Work::FIELD_USER_ID, $user_id)->get();
        }
        else {
           $works = Work::all();
        }    

        //вакансии закончились сроки
        foreach ($works as $work) {
          $date_close = strtotime($work->date_close);
          $current_date = time();           
          if ($date_close < $current_date && $work->date_close !== NULL && $work->pay_confirm == 1) {
           $work->pay_confirm = 0;  
           $work->status = 1;          
           $work->save();
           CompanyController::countCompanyUserInfo($work->user_id);
         } 
        }
   
         
         foreach ($works as $work) {
 
           $work->link = Work::get_link($work->slug);

           $work->cost = intval($work->cost);
           $work->balance = intval($work->balance);
           $work->balance_admin = intval($work->balance_admin);
           $work->balance_free = intval($work->balance_free);
           $work->deposit_in = intval($work->deposit_in);

            $closed_date = strtotime($work->date_close);
            $current_date = time();

            $work->created = date('d.m.y H:i', strtotime($work->created_at));

            if (!empty( $work->date_close)) {
               $work->date_close = date('d.m.y H:i', strtotime($work->date_close));
            } else {
               $work->date_close = "-";
            }     
            
            if (!empty( $work->date_create)) {
              $work->date_create = date('d.m.y H:i', strtotime($work->date_create));
            } else {
              $work->date_create = "-";
            }  
            

             //вакансия закончилась срок
            if ($closed_date < $current_date && $work->date_close !== "-") {
                $work->closed = true;
            } else {
                $work->closed = false;
            }            
            
            
            $work->company_name = isset($work->company()->name) ? $work->company()->name : "";

            $work->count_candidates = Questionaire::where('work_id', $work->id)->whereIn("status_now",[1,3])->count();
            
            $work->count_candidates_approved = Questionaire::where('work_id', $work->id)->where('status',1)->count();

            $work->count_candidates_viewed = Questionaire::where('work_id', $work->id)->where('opened', 1)->count();

         }


         return response()->json($works->toArray());
    }


    public function index_admin(Request $request) {

       $limit = isset($request->limit) ? $request->limit : 5;
       $offset = isset($request->offset) ? $request->offset : 0;
       $sort = isset($request->sort) ? $request->sort : "id";

        if (isset($request->asc) && ($request->asc === true)) {
            $asc = 'asc';
        } else {
            $asc = 'desc';
        }

        if (isset($request->user_id)) {
           $user_id = $request->user_id;
           $works = Work::where(Work::FIELD_USER_ID, $user_id)->orderBy($sort, $asc)->take($limit)->skip($offset)->get();
        } else {
           $works = Work::orderBy($sort, $asc)->take($limit)->skip($offset)->get();
        }     

        foreach ($works as $work) {
          $date_close = strtotime($work->date_close);
          $current_date = time();           
          if ($date_close < $current_date && $work->date_close !== NULL && $work->pay_confirm == 1) {
           $work->pay_confirm = 0;  
           $work->status = 1;          
           $work->save();
           CompanyController::countCompanyUserInfo($work->user_id);           
         } 
        }

        foreach ($works as $work) {
 
           $work->link = Work::get_link($work->slug);

           $work->cost = intval($work->cost);
           $work->balance = intval($work->balance);
           $work->balance_admin = intval($work->balance_admin);
           $work->balance_free = intval($work->balance_free);
           $work->deposit_in = intval($work->deposit_in);

           $work->sale = intval($work->sale);

 
            if (!empty( $work->date_close)) {
              $work->date_close = date('d.m.y H:i', strtotime($work->date_close));
            } else {
              $work->date_close = "-";
            }
            if (!empty( $work->date_create)) {
              $work->date_create = date('d.m.y H:i', strtotime($work->date_create));
            } else {
              $work->date_create = "-";
            }            
                         
            $work->company_name = isset($work->company()->name) ? $work->company()->name : "";
            
            $work->count_candidates_approved = Questionaire::where('work_id', $work->id)->where('status',1)->count();
         }

         return response()->json($works->toArray());
    } 

    public function create()
    {
        
    }

    public function store(Request $request)
    {    
        $user_id = $request->get('user_id');
        $name = $request->get('name');

        //если вакансия повторная
        $work = Work::where('user_id',$user_id)->where('name',$name)->first();
        if (!empty($work)) {
            return response(['status' => 2, 'msg' => __('text.VacancyExistsMsg')], 200);
        }        

        //специализации
        $specializations = Specialization::implode_specializations($request->get('specializations'));

        //опыт
        $experiences = [];
         if (!empty($request->get('experiences'))){ 
            $experiences = $request->get('experiences');
         }
        $experiences = json_encode($experiences, JSON_UNESCAPED_UNICODE);
        
        //навыки
        $skills = [];
         if (!empty($request->get('skills'))){ 
            $skills = $request->get('skills');
         }
        $skills = json_encode($skills, JSON_UNESCAPED_UNICODE);
        
        //языки
        $languages = Language::language_encode($request->get('languages'));

        //права        
        $licences = CarLicence::license_encode($request->get('licences'));      

        //Владение собственным автомобилем    
        $type_car = Car::car_encode($request->get('type_car'));    

        //тип договора
        $type_contract = [];
        if (!empty($request->get('type_contract'))) {
            $type_contract = $request->get('type_contract');
        }
        $type_contract = json_encode($type_contract, JSON_UNESCAPED_UNICODE);

        //занятость
        $employment = [];  
        if (!empty($request->get('employment')[0])) {
            $employment = $request->get('employment');
        }
        $employment = json_encode($employment, JSON_UNESCAPED_UNICODE);


        //режим работы   
        $work_time = [];          
        if (!empty($request->get('work_time')[0])) {
            $work_time = $request->get('work_time');
        }
        $work_time = json_encode($work_time, JSON_UNESCAPED_UNICODE);


        //льготы
        $facilitation = [];        
        if (!empty($request->get('facilitation')[0])) {
            $facilitation = $request->get('facilitation');
        }
        $facilitation = json_encode($facilitation, JSON_UNESCAPED_UNICODE);

        $personal = Personal::personal_encode($request->get('personal'));

        $current_date = time();        
        $date_close = NULL;
        $date_create = date('Y-m-d h:i:s', $current_date);
        $content = "";    

        
        if (!empty($request->get('income_end'))) {
          $income = (int) $request->get('income_end');
        } else {
          $income = 0;
        }       

        if (!empty($request->get('income_start'))) {
          $income_start = (int) $request->get('income_start');
        } else {
          $income_start = 0;
        }        
       

        if (!empty($request->get('income_end'))) {
          $income_end = (int) $request->get('income_end');
        } else {
          $income_end = 0;
        }              


        $cost = 0; 
        $deposit_in = 0; 
        $status = (int) $request->get('status');

        //черновик
        if ($status === 1) { 
           $deposit_text = 0; 
        }

        //если статус в работе
        if ($status === 2) { 
          $deposit_text = 2; 
          $status = 1;
          $deposit_in = $cost;
        }

        $slug = Work::generateRandomString(10);

        $sale = 0;
        $pay_confirm = 0;        

        if (!empty($request->get('currency'))) {
            $user_company = User::find($user_id);
            $user_company->currency = $request->get('currency');
            $user_company->save();
        }

       $prepareData = [

            Work::FIELD_USER_ID => $user_id,
            Work::FIELD_SLUG => $slug,
            Work::FIELD_STATUS =>  $status,

            Work::FIELD_COST => $cost,

            Work::FIELD_BALANCE => 0,
            Work::FIELD_BALANCE_ADMIN => 0,            
            Work::FIELD_BALANCE_FREE => 0, 

            Work::FIELD_DEPOSIT_TEXT => $deposit_text, //депозит  Поиск идет

            Work::FIELD_DEPOSIT_IN => $deposit_in, //депозит Внести на депозит
            Work::FIELD_DEPOSIT_CONFIRM => 0, //депозит Подтвердить внесение на депозит Не нужно

            Work::FIELD_SALE => $sale,
            Work::FIELD_PAY_CONFIRM => $pay_confirm,
            Work::FIELD_DATE_CREATE => $date_create,

            Work::FIELD_DATE_CLOSE => $date_close,

            Work::FIELD_PUBLISH_COMPANY => $request->get('publish_сompany') ?  1 : 0,
            Work::FIELD_NAME => $request->get('name'),   
            Work::FIELD_POSITIONS   => $request->get('positions'),
            Work::FIELD_CITY_ID   => $request->get('city_id'),
            Work::FIELD_SPECIAL   => $specializations,
            Work::FIELD_IS_VIEW_SPECIAL    => $request->get('is_view_special') ?  1 : 0,

            Work::FIELD_GENDER   => $request->get('gender'),
            Work::FIELD_AGE_START  => $request->get('age_start'),
            Work::FIELD_AGE_END  => $request->get('age_end'),

            Work::FIELD_EDUCATION  => $request->get('education'),
            Work::FIELD_TYPE_EDUCATION  => $request->get('type_education'),
        
            Work::FIELD_SET_IS_COUTCH=> $request->get('set_is_coutch') ?  1 : 0,
          
            Work::FIELD_EXPERIENCES   => $experiences,
            Work::FIELD_SKILLS        => $skills,

            Work::FIELD_EXPERIENCE_WORK_YEAR_ALL => $request->get('experience_work_year_all'),
            Work::FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL   => $request->get('experience_work_base_year_all'),
            Work::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE   => $request->get('count_work_people'),
            Work::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL   => $request->get('count_work_people_all'),

            Work::FIELD_LANGUAGES        => $languages,
            Work::FIELD_LICENCES        => $licences,
            Work::FIELD_TYPE_CAR        => $type_car,
            Work::FIELD_TYPE_CONTRACT        => $type_contract,

            Work::FIELD_INCOME => $income,
            Work::FIELD_INCOME_START=> $income_start,
            Work::FIELD_INCOME_END => $income_end,

            Work::FIELD_CURRENCY     => $request->get('currency'), 
            Work::FIELD_PUBLISH_INCOME     => $request->get('publish_income') ?  1 : 0,    

            Work::FIELD_EMPLOYMENT        => $employment,
            Work::FIELD_WORK_TIME        => $work_time,
            Work::FIELD_FACILITATION        => $facilitation,
            Work::FIELD_PERSONAL        => $personal,

            Work::FIELD_CONTENT   => $content,        
            
        ];

        $work = $this->worksRepository->createWork($prepareData);

        CompanyController::countCompanyUserInfo($user_id);

        return response(['status' => 1, 'msg' => __('text.VacancySaveMsg'), 'work'=> $work], 200);

    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $work_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $work_id)
    {
        $work = Work::find($work_id);

        if (!empty($work)) {

        $specializations = Specialization::implode_specializations($request->get('specializations'));  

        $experiences = [];
         if (!empty($request->get('experiences'))){ 
            $experiences = $request->get('experiences');
         }
        $experiences = json_encode($experiences, JSON_UNESCAPED_UNICODE);

        $skills = [];
         if (!empty($request->get('skills'))){ 
            $skills = $request->get('skills');
         }
        $skills = json_encode($skills, JSON_UNESCAPED_UNICODE);
        
        $languages = Language::language_encode($request->get('languages'));
            
        $licences = CarLicence::license_encode($request->get('licences'));        

     
        $type_car = Car::car_encode($request->get('type_car'));    


        $type_contract = [];
        if (!empty($request->get('type_contract'))) {
            $type_contract = $request->get('type_contract');
        }
        $type_contract = json_encode($type_contract, JSON_UNESCAPED_UNICODE);

        $employment = [];  
        if (!empty($request->get('employment')[0])) {
            $employment = $request->get('employment');
        }
        $employment = json_encode($employment, JSON_UNESCAPED_UNICODE);
 
        $work_time = [];          
        if (!empty($request->get('work_time')[0])) {
            $work_time = $request->get('work_time');
        }
        $work_time = json_encode($work_time, JSON_UNESCAPED_UNICODE);


        //льготы
        $facilitation = [];        
        if (!empty($request->get('facilitation')[0])) {
            $facilitation = $request->get('facilitation');
        }
        $facilitation = json_encode($facilitation, JSON_UNESCAPED_UNICODE);

        $personal = Personal::personal_encode($request->get('personal'));

        $user_id = $request->get('user_id');
        if (!empty($request->get('income_end'))) {
          $income = (int) $request->get('income_end');
        } else {
          $income = 0;
        }     


        $cost = 0; 

        if (!empty($request->get('income_start'))) {
          $work->income_start = (int) $request->get('income_start');
        } else {
          $work->income_start = 0;
        }       

        if (!empty($request->get('income_end'))) {
          $work->income_end = (int) $request->get('income_end');
        } else {
          $work->income_end = 0;
        }    

        $work->publish_company =  $request->get('publish_company') ?  1 : 0;
        $work->name = $request->get('name');
        $work->positions =  $request->get('positions');
        $work->city_id = $request->get('city_id');
        $work->special =  $specializations;

        $work->is_view_special = $request->get('is_view_special') ?  1 : 0;

        $work->gender = $request->get('gender');
        $work->age_start = $request->get('age_start');
        $work->age_end =  $request->get('age_end');

        $work->education    = $request->get('education');
        $work->type_education =  $request->get('type_education');
    
        $work->set_is_coutch =  $request->get('set_is_coutch') ?  1 : 0;
      
        $work->experiences = $experiences;
        $work->skills = $skills;

        $work->experience_work_year_all = $request->get('experience_work_year_all');
        $work->experience_work_base_year_all = $request->get('experience_work_base_year_all');
        $work->count_work_people = $request->get('count_work_people');
        $work->count_work_people_all = $request->get('count_work_people_all');

        $work->languages = $languages;
        $work->licences =  $licences;
        $work->type_car =  $type_car;
        $work->type_contract =  $type_contract;

        $work->income = $income;
        $work->currency = $request->get('currency');
        $work->publish_income = $request->get('publish_income') ?  1 : 0;

        $work->employment =  $employment;
        $work->work_time =   $work_time;
        $work->facilitation =  $facilitation;
        $work->personal =  $personal;

        $work->cost = $cost;         
              
       
        $deposit_in = 0;
        $status = (int) $request->get('status');

        if ($status === 1) { 
           $deposit_text = 0; // 0 1 2 Действия не нужны, Внесен, Нужно внести
        }       

           $work->save();
           return response(['status' => 1, 'msg' => __('text.VacancyEditedMsg'), 'data' => $work], 200);
        }
    }


    public function show($id)
    {
        $work = Work::find($id);

        if (!empty($work)) {

        $work->link = Work::get_link($work->slug);

        $work->region_text = City::get_region_text($work->city_id);
        

        $work->special = Specialization::explode_specializations($work->special); 
       
        //опыт
        $experiences = json_decode($work->experiences);
        $work->experiences = $experiences;

        //навыки
        $skills = json_decode($work->skills);
        $work->skills = $skills;

        //тип договора
        $type_contract  = json_decode($work->type_contract);
        $work->type_contract = $type_contract;

        //занятость
        $employment  = json_decode($work->employment);
        $work->employment = $employment;

        $languages = json_decode($work->languages);
        $work->languages = $languages;
           
        $licences = json_decode($work->licences);
        $work->licences = $licences;

        $type_car = json_decode($work->type_car);
        $work->type_car = $type_car;

        $work_time = json_decode($work->work_time);
        $work->work_time = $work_time;

        $facilitation = json_decode($work->facilitation);
        $work->facilitation = $facilitation;

        $personal = json_decode($work->personal);
        $work->personal = $personal;

        $company = User::find($work->user_id);

        $work->cost = intval($work->cost);
        $balance = intval($company->balance);
        $work->balance =  intval($work->balance);        
        $work->balance_admin = intval($work->balance_admin);
        $work->balance_free = intval($work->balance_free);
        $work->deposit_in = intval($work->deposit_in);

        $work->sale = intval($work->sale);

        // Открытие контактных данных каждого кандидата
        $sum_open = Work::get_sum_open($work->income, $work->sale);        
        $work->sum_open = $sum_open;
        $sum_open_show_button = false;
        if ($sum_open <= $balance) {
          $sum_open_show_button = true;
        }
        $work->sum_open_show_button = $sum_open_show_button;

        // стоимость замены каждого кандидата        
        $sum_replace = Work::get_sum_replace($work->income, $work->sale);        
        $work->sum_replace = $sum_replace;
        $sum_replace_show_button = false;
        if ($sum_replace <= $balance) {
          $sum_replace_show_button = true;
        }
        $work->sum_replace_show_button = $sum_replace_show_button;


        $work->count_candidates = Questionaire::where('work_id', $work->id)->whereIn("status_now",[1,3])->count();          
        $work->count_candidates_viewed = Questionaire::where('work_id', $work->id)->where('opened', 1)->count();
        if (isset($work->count_candidates) && isset($work->count_candidates_viewed)) {
          $work->count_candidates_waited = $work->count_candidates - $work->count_candidates_viewed;
        } else {
          $work->count_candidates_waited = 0;
        }

            return response(['status' => 1, 'data' => $work], 200);
        }
    }


    public function edit($id)
    {
       $work = Work::find($id);
        if (!empty($work)) {

            $city_id = $work->city_id;           

            $work->region_id = City::get_region_id($work->city_id);

            $work->country_id = City::get_country_id($work->region_id);

            $work->special = Specialization::explode_edit_specializations($work->special);

             //опыт
            $experiences = json_decode($work->experiences);
            $work->experiences = $experiences;

            //навыки
            $skills = json_decode($work->skills);
            $work->skills = $skills;

            $type_contract = json_decode($work->type_contract);
            $work->type_contract = $type_contract;

            $languages = json_decode($work->languages);
            $work->languages = $languages;

            $licences = json_decode($work->licences);
            $work->licences = $licences;

            $type_car = json_decode($work->type_car);
            $work->type_car = (!empty($type_car)) ? $type_car : [];

            $employment = json_decode($work->employment);
            $work->employment = (!empty($employment)) ? $employment : [];

            $work_time = json_decode($work->work_time);
            $work->work_time = $work_time;

            $facilitation = json_decode($work->facilitation);
            $work->facilitation = $facilitation;

            $personal_array = json_decode($work->personal);
            $personal = [];
            foreach ($personal_array as $personal_key => $personal_value) {
                $personal_item = [];
                if (!empty($personal_value)) {
                    $personal_item["id"] = $personal_key;
                    $personal_item["text_category"] = "";
                    $personal_item["text_children"] = "";                  
                    $personal_item["show_other"] = true;
                    $personal_item["text"] = $personal_value;
                    array_push($personal, $personal_item);
                }
            }
            $work->personal = $personal;          

           return response(['status' => 1, 'data' => $work], 200);
        }
    }


    public function status(Request $request) {
      $work_id = (int) $request->get('work_id');
      $new_status = (int) $request->get('new_status');
      $user_id = (int) $request->get('user_id');
       if ($work_id > 0 && $new_status > 0 && $user_id > 0) {
          $work = Work::find($work_id);
          if (!empty($work)) {
            $work->status = $new_status;
            $work->save();
            CompanyController::countCompanyUserInfo($user_id);
            return response(['status' => 1, 'msg' => 'Статус изменен'], 200);
          }
       }
    }



    public function send(Request $request) {
      $work_id = (int) $request->get('work_id');
      //$new_status = 2;
      $user_id = (int) $request->get('user_id');
      
      //$deposit_in = 0;
      if ($work_id > 0 && $user_id > 0) {
        $work = Work::find($work_id);
        if (!empty($work)) {
          if ($work->pay_confirm === 1) {
            $status = 2;
            $work->status = $status; 
            $work->save();
            CompanyController::countCompanyUserInfo($user_id);
            return response(['status' => 1, 'msg' => __('text.VacancySendWorkMsg')], 200);
          }
        }        
      }
      return response(['status' => 0, 'msg' => __('text.VacancyPayPublicationMsg')], 200);
    }


    public function time(Request $request) {
      $work_id = (int) $request->get('work_id');     
      $user_id = (int) $request->get('user_id');
      //$deposit_in = 0;
      if ($work_id > 0 && $user_id > 0) {
        $work = Work::find($work_id);
        if (!empty($work)) {
          if ($work->pay_confirm === 1) {
            $status = 2;
            $work->status = $status; 
            $work->save();
            CompanyController::countCompanyUserInfo($user_id);
            return response(['status' => 1, 'msg' => __('text.VacancySendWorkMsg')], 200);
          }
        }   
      }
      return response(['status' => 0, 'msg' => __('text.VacancyPayPublicationMsg')], 200);
    }

    public function sale(Request $request)
    {
      $work_id = (int) $request->get('work_id');
      $value = (int) $request->get('value');
      if ($work_id > 0 && $value >= 0 && $value < 100) {
        $work = Work::find($work_id);
        if (!empty($work)) {
          $work->sale = intval($value);
          $work->save();
          return response(['status' => 1, 'msg' => 'Скидка сохранена'], 200);
        }
      }
      return response(['status' => 0, 'msg' => __('text.Error')], 200);
    }


    public function pay_confirm(Request $request)
    {
      $work_id = $request->get('work_id');
      $pay_value = $request->get('pay_value');
      $work = Work::find($work_id);
      $user_id = $work->user_id;

      if (!empty($work)) {
          if ($work->pay_confirm === 0 && $pay_value === 1) {
            $work->pay_confirm = 1;
            if (!empty($work->date_close)) {
                $work->status = 2;
            }
            $work->date_close = Work::get_date_close();
            $work->save();
            CompanyController::countCompanyUserInfo($user_id);
            return response(['status' => 1, 'msg' => 'Оплата подтверждена'], 200);
          }  
          if ($work->pay_confirm === 1 && $pay_value === 0) {
            $work->pay_confirm = 0;      
            $work->save();
            CompanyController::countCompanyUserInfo($user_id);
            return response(['status' => 1, 'msg' => 'Оплата отменена'], 200);
          }                   
      }
      return response(['status' => 0, 'msg' => __('text.Error')], 200);
    }

}
