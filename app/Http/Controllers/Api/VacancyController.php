<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CompanyController;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use App\Models\Specialization;
use App\Models\Personal;
use App\Models\Questionaire;
use App\Models\FileUpload;
use App\Models\Language;
use App\Models\Car;
use App\Models\CarLicence;

//api 
class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $works = Work::all();


         foreach ($works as $work) {

           $work->link = Work::get_link($work->slug);

           $work->cost = intval($work->cost);
           $work->balance = intval($work->balance);
           $work->balance_admin = intval($work->balance_admin);
           $work->balance_free = intval($work->balance_free);
           $work->deposit_in = intval($work->deposit_in);
           $work->sale = intval($work->sale);

            $work->created = date('d.m.y H:i', strtotime($work->created_at));
          
            if (!empty( $work->date_close)) {
               $work->date_close = date('d.m.y H:i', strtotime($work->date_close));
             } else {
                $work->date_close = "-";
             }
                        
            $work->company_name = isset($work->company()->name) ? $work->company()->name : "";

            $work->languages = json_decode($work->languages);
            $work->type_contract = json_decode($work->type_contract);
            $work->licences = json_decode($work->licences);
            $work->type_car = json_decode($work->type_car);
            $work->experiences = json_decode($work->experiences);
            $work->skills = json_decode($work->skills);
            $work->employment = json_decode($work->employment); 
            $work->work_time = json_decode($work->work_time); 
            $work->facilitation = json_decode($work->facilitation); 
            $work->personal = json_decode($work->personal); 

         }

         return response()->json($works->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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


        //Личностные данные
        $personal = Personal::personal_encode($request->get('personal'));

        //дата закрытия в начале нет
        $current_date = time();        
        $date_close = NULL;
        $date_create = date('Y-m-d h:i:s', $current_date);

        $content = "";    

        $user_id = $request->get('user_id');
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

        $cost = intval(0.42 * $income); //размер депозита
        $deposit_in = 0; //требуется внести
        $status = (int) $request->get('status');

        //черновик
        if ($status === 1) { 
           $deposit_text = 0; // 0 1 2 Действия не нужны, Внесен, Нужно внести
        }

        //если статус в работе
        if ($status === 2) { 
          $deposit_text = 2; // 0 1 2 Действия не нужны, Внесен, Нужно внести
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

            Work::FIELD_GENDER     => $request->get('gender'),
            Work::FIELD_AGE_START         => $request->get('age_start'),
            Work::FIELD_AGE_END        => $request->get('age_end'),

            Work::FIELD_EDUCATION        => $request->get('education'),
            Work::FIELD_TYPE_EDUCATION        => $request->get('type_education'),
        
            Work::FIELD_SET_IS_COUTCH      => $request->get('set_is_coutch') ?  1 : 0,
          
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

            Work::FIELD_INCOME     => $income,
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

        $work = Work::create($prepareData);

        CompanyController::countCompanyUserInfo($user_id);

        return response(['status' => 1, 'msg' => __('text.VacancySaveMsg'), 'work'=> $work], 200);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::find($id);

        if (!empty($work)) {

        $work->cost = intval($work->cost);
        $work->balance = intval($work->balance);
        $work->balance_admin = intval($work->balance_admin);
        $work->balance_free = intval($work->balance_free);
        $work->deposit_in = intval($work->deposit_in);
        $work->sale = intval($work->sale);
          
        $work->link = Work::get_link($work->slug);

        $work->region_text = City::get_region_text($work->city_id);
        

        $work->special = Specialization::explode_specializations($work->special);


        $experiences = json_decode($work->experiences);
        $work->experiences = $experiences;

 
        $skills = json_decode($work->skills);
        $work->skills = $skills;


        $type_contract  = json_decode($work->type_contract);
        $work->type_contract = $type_contract;


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

        $work->count_candidates = Questionaire::where('work_id', $work->id)->where('status_now',1)->count();
        $work->count_candidates_viewed = Questionaire::where('work_id', $work->id)->where('opened', 1)->count();



        $questionnaires = Questionaire::where("work_id", $work->id)->where('status',1)->get();
        foreach ($questionnaires as $question_item) {

            //$opened = $question_item->opened;
            $opened = 1;
          
            $hide = "Скрыто";
  
            //регион
            $question_item->region_text = City::get_region_text($question_item->city_id);

          
            $birthday = date('d.m.Y', strtotime($question_item->birthday));

            $question_item->birthday = ($opened) ? $birthday  : $hide ;

            //данные скрытые
            $question_item->fio_f = ($opened) ? $question_item->fio_f  : $hide ;
            $question_item->fio_i = ($opened) ? $question_item->fio_i  : $hide ;
            $question_item->fio_o = ($opened) ? $question_item->fio_o  : $hide ;

            $question_item->phone = ($opened) ? $question_item->phone  : $hide ;
            $question_item->email = ($opened) ? $question_item->email  : $hide ;

            $question_item->gender = ($opened) ? $question_item->gender  : $hide ;


            //страны
            $question_item->country = Country::get_countries_question($question_item->country);

            $question_item->special = Specialization::explode_specializations($question_item->special);

            $question_item->experiences = json_decode($question_item->experiences);  

            $question_item->skills = json_decode($question_item->skills);
          
            $question_item->education = json_decode($question_item->education);

            $question_item->type_education = json_decode($question_item->type_education);

            $languages = json_decode($question_item->languages);
            $question_item->languages = $languages;

            $licences = json_decode($question_item->licences);
            $question_item->licences = $licences;


            $type_car = json_decode($question_item->type_car);
            $question_item->type_car = $type_car;
            
            $question_item->personal = json_decode($question_item->personal);
            $question_item->work_company = json_decode($question_item->work_company);            
        
  
            $question_item->photo = FileUpload::get_photo_url($question_item->photo, $opened);
            $question_item->resume = FileUpload::get_resume_url($question_item->resume, $opened);


            //причины
            $question_item->reason = json_decode($question_item->reason);

            //комментарии
            $content = json_decode($question_item->content);
            if (!isset($content->expert_comment)) { $content["expert_comment"] = ""; }
            if (!isset($content->type_contract_comment)) { $content["type_contract_comment"] = ""; }
            if (!isset($content->employment_comment)) { $content["employment_comment"] = ""; }
            if (!isset($content->work_time_comment)) { $content["work_time_comment"] = "";    } 

            $question_item->content = $content;


        }


            $work->candidates = $questionnaires;

            return response(['data' => $work], 200);
        
        }
         return response([], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $work_id = (int) $id;

        $work = Work::find($work_id);


        if (!empty($work)) {

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

        //Личностные данные
        $personal = Personal::personal_encode($request->get('personal'));

        
        $current_date = time();
        $date_close = NULL;

        $user_id = $request->get('user_id');
        if (!empty($request->get('income_end'))) {
          $income = (int) $request->get('income_end');
        } else {
          $income = 0;
        }        
        $cost = intval(0.42 * $income); //размер депозита

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
      
        if(isset($request->publish_company)) {  
        $work->publish_company =  $request->get('publish_company') ?  1 : 0;
        }

        if(isset($request->name)) {  
            $work->name = $request->get('name');
        }

        if(isset($request->positions)) {  
        $work->positions =  $request->get('positions');
        }

        if(isset($request->city_id)) { 
        $work->city_id = $request->get('city_id');
        }

        if(isset($request->specializations)) { 
        $work->special =  $specializations;
        }

        if(isset($request->is_view_special)) { 
        $work->is_view_special = $request->get('is_view_special') ?  1 : 0;
        }

        if(isset($request->gender)) { 
        $work->gender = $request->get('gender');
        }

        if(isset($request->age_start)) { 
        $work->age_start = $request->get('age_start');
        }

        if(isset($request->age_end)) { 
        $work->age_end = $request->get('age_end');
        }

        if(isset($request->education)) { $work->education    = $request->get('education'); }
        if(isset($request->type_education)) { $work->type_education =  $request->get('type_education'); }
    
        if(isset($request->set_is_coutch)) {  $work->set_is_coutch =  $request->get('set_is_coutch') ?  1 : 0; }
      
        if(isset($request->experiences)) {  $work->experiences = $experiences; }
        if(isset($request->skills)) {  $work->skills = $skills; }

        if(isset($request->experience_work_year_all)) {  $work->experience_work_year_all = $request->get('experience_work_year_all'); }
        if(isset($request->experience_work_base_year_all)) {  $work->experience_work_base_year_all = $request->get('experience_work_base_year_all'); }
        if(isset($request->count_work_people)) {  $work->count_work_people = $request->get('count_work_people'); }

         if(isset($request->count_work_people_all)) {  $work->count_work_people_all = $request->get('count_work_people_all'); }

         if(isset($request->languages)) {  $work->languages = $languages; }
         if(isset($request->licences)) {  $work->licences =  $licences; }
         if(isset($request->type_car)) {  $work->type_car =  $type_car; }
         if(isset($request->type_contract)) {  $work->type_contract =  $type_contract; }

         if(isset($request->income)) {  
            $work->income = $income;
            $work->cost = $cost;         
         }
         if(isset($request->currency)) {  $work->currency = $request->get('currency'); }
         if(isset($request->publish_income)) {  $work->publish_income = $request->get('publish_income') ?  1 : 0; }

         if(isset($request->employment)) {  $work->employment = $employment; }
         if(isset($request->work_time)) {  $work->work_time =  $work_time; }
         if(isset($request->facilitation)) {  $work->facilitation = $facilitation; }
         if(isset($request->personal)) {  $work->personal =  $personal; }
             
       
        $deposit_in = 0;
        $status = (int) $request->get('status');

        //черновик
        if ($status === 1) { 
           $deposit_text = 0; // 0 1 2 Действия не нужны, Внесен, Нужно внести
        }       

        //списать депозит, если статус в работе
        if ($status === 2) {
          $work->date_close = $date_close;
          if ($cost > 0) {
             $work_balance = $work->balance;
             if ($work_balance >= $cost) {
               $deposit_text = 1; // 0 1 2 Действия не нужны, Внесен, Нужно внести
               $work->deposit_text = $deposit_text;
               $work->deposit_in = $deposit_in;
               $status = 2;
               $work->status = $status;               
            } else {
              //если не хватает на балансе
              $deposit_text = 2; // 0 1 2 Действия не нужны, Внесен, Нужно внести
              $status = 1;
              $work->status = $status;
              if ($work_balance > 0) {
                $deposit_in = $cost - $work_balance;
              } else {
                $deposit_in = $cost;
              }              
              $work->deposit_text = $deposit_text;
              $work->deposit_in = $deposit_in;
            }           
          }
        }

           $work->save();
           return response(['status' => 1, 'msg' => __('text.Changed'), 'data' => $work], 200);
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemId = (int) $id;
        $work = Work::find($itemId);
        if (!empty($work)) {
            $companyId = $work->user_id;
            CompanyController::countCompanyUserInfo($companyId);
            $work = Work::destroy($itemId);
            return response(['status' => 1, 'msg' => __('text.Deleted')], 200);
        } else {
            return response(['status' => 0, 'msg' => __('text.NotFound')], 200);
        }
    }
}
