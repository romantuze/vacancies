<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use App\Models\Questionaire;
use App\Models\Education;
use App\Models\TypeEducation;
use App\Models\Car;
use App\Models\CarLicence;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Specialization;
use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use App\Models\Work;
use App\Models\User;
use App\Models\FileUpload;
use App\Models\SkillLevel;
use Illuminate\Support\Facades\Storage;
use Auth;

//api 
class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questionnaires = Questionaire::all();

        $user = User::find(1);  
        $lang = 'ru';      
        if (!empty($user) && !empty($user->lang)) {
          $lang = $user->lang;
        }  
        
        $hide = ($lang === 'ru') ? 'Скрыто' : 'Hidden';

        foreach ($questionnaires as $question_item) {

            //$opened = $question_item->opened;
            $opened = 1;

            //регион
            $question_item->region = City::get_region_text($question_item->city_id);

            //возраст 
            $age = "";
            $birthday = date('d.m.Y', strtotime($question_item->birthday));

            if (!empty($birthday)) {
               list($day,$month,$year) = explode(".",$birthday);
               $year_diff  = date("Y") - $year;
               $month_diff = date("m") - $month;
               $day_diff   = date("d") - $day;
               if ($day_diff < 0 && $month_diff==0){$year_diff--;}
               if ($day_diff < 0 && $month_diff < 0){$year_diff--;}
               $age = $year_diff; 
            } 
            $question_item->birthday = $birthday;
            $question_item->age = $age;


            //данные скрытые
            $question_item->fio_f = ($opened) ? $question_item->fio_f  : $hide ;
            $question_item->fio_i = ($opened) ? $question_item->fio_i  : $hide ;
            $question_item->fio_o = ($opened) ? $question_item->fio_o  : $hide ;

            $question_item->phone = ($opened) ? $question_item->phone  : $hide ;
            $question_item->email = ($opened) ? $question_item->email  : $hide ;


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

        return response()->json($questionnaires->toArray());
    }


    public function create()
    {

    }


    public function store(Request $request)
    {       
        $work_id = (int) $request->get('work_id');
        $work = Work::find($work_id);      

        //одинаковые заявки
        $email = strtolower($request->get('email'));
        $phone = $request->get('phone');
        $questionaire = Questionaire::where('work_id', $work_id)->where('email', $email)->where('phone',$phone)->first();
        if (!empty($questionaire)) {
          return response(['status' => 2, 'msg' => __('text.ApplicationAlreadySubmitted')], 200);
        }

        //проверка анкетирования 0 не прошел 1 прошел
        $status =  $request->get('status');
        
        $status_reason = NULL;

        if (isset($request->rate)) {
          $status_rate = intval($request->get('rate')); 
        } else {
          $status_rate = 0; 
        }
        
          //страны          
          if (!empty($request->get('countries'))) {
              $countries = $request->get('countries');
          } else {
            $countries = [];
          }
          $countries = json_encode($countries, JSON_UNESCAPED_UNICODE);

        //специализации
        $specializations = Specialization::implode_specializations($request->get('specializations')); 

          //образование          
          if (!empty($request->get('education'))) {
            $education = $request->get('education');
          } else {
            $education = [];
          }
          $education = json_encode($education, JSON_UNESCAPED_UNICODE);

          $type_education = [];
          if (!empty($request->get('type_education'))) {
            $type_education = $request->get('type_education');
          }
          $type_education = json_encode($type_education, JSON_UNESCAPED_UNICODE);



          //обязанности          
          if (!empty($request->get('experiences'))) {   
             $experiences = $request->get('experiences');   
          } else {
            $experiences = [];
          }
          $experiences = json_encode($experiences, JSON_UNESCAPED_UNICODE);


          //навыки
          $skills = [];
          $skill_levels = $request->get('skills');
          foreach ($skill_levels as $array_key => $array_value) {
              $array_item = [];
              $array_item["id"] = $array_key;
              $array_item["level"] = $array_value;
              array_push($skills, $array_item);
          }

          $skills = json_encode($skills, JSON_UNESCAPED_UNICODE);


          // Работа в компаниях
          
          if (!empty($request->get('work_company'))) {
            $work_company = $request->get('work_company');
          } else {
            $work_company = [];
          }
          $work_company = json_encode($work_company, JSON_UNESCAPED_UNICODE);

 
  
          //языки   
           $languages = [];        
          if (!empty($request->get('languages'))) { 
            $languages_request = $request->get('languages');
            if (!empty($languages_request[0]['text'])) {
              $languages = $languages_request;
            }
          } else {           
          }
          $languages = json_encode($languages, JSON_UNESCAPED_UNICODE);

          //права
          $licences = [];
          if (!empty($request->get('licences'))) {            
            $licences_request = $request->get('licences');
            if (!empty($licences_request[0]['text'])) {
              $licences = $licences_request;
            } 
          } 
          $licences = json_encode($licences, JSON_UNESCAPED_UNICODE);


          //тип авто
          if (!empty($request->get('type_car'))) {
            $type_car = $request->get('type_car');
          } else {
            $type_car = [];
          }
          $type_car = json_encode($type_car, JSON_UNESCAPED_UNICODE);

          
          $type_contract = $request->get('type_contract_ok');          
    
          //Занятость
          $employment = !empty($request->get('employment_ok')) ? $request->get('employment_ok') : NULL; 

          //Режим работы
          $work_time = !empty($request->get('work_time_ok')) ? $request->get('work_time_ok') : NULL;

          //Личностные данные          
          if (!empty($request->get('personal'))) {
              $personal = $request->get('personal');
          } else {
            $personal = [];  
          }
          $personal = json_encode($personal, JSON_UNESCAPED_UNICODE); 

          
         /* $user_id = 1;*/

          //резюме
          $resume = !empty($request->get('resume')) ? $request->get('resume') : NULL;

          $photo = !empty($request->get('photo')) ? $request->get('photo') : NULL;


          //комментарии 
          $content = [];
          $content["expert_comment"] = "";
          $content["type_contract_comment"] = "";
          $content["employment_comment"] = "";
          $content["work_time_comment"] = "";
          $content_json = json_encode($content, JSON_UNESCAPED_UNICODE);

          //Текущий статус»: «Скачен с сорсинга» (по умолчанию) 0
          $status_now =  $request->get('status_now') ?  $request->get('status_now') : 0;

          //желаемый доход
          /*$income = !empty($request->get('income')) ? $request->get('income') : NULL;*/
          $income_ok = $request->get('income_ok') ?  1 : 0;

          if ($income_ok === 1) {
            $income_want = $work->income;
          } else {
            $income_want = !empty($request->get('income_want')) ? $request->get('income_want') : NULL;  
          }        

         $opened = $request->get('opened') ?  1 : 0;

          $ref = NULL;
          if (!empty($request->ref)) {
            $ref = $request->ref;
          }
        
          $prepareData = [               
         
              Questionaire::FIELD_WORK_ID => $work_id,     

              Questionaire::FIELD_REF => $ref,          
         
              Questionaire::FIELD_OPENED => $opened,     

              Questionaire::FIELD_STATUS => $status,
              Questionaire::FIELD_STATUS_NOW => $status_now,    
  
              Questionaire::FIELD_PHONE => $request->get('phone'),  
              Questionaire::FIELD_EMAIL => $email,  

              Questionaire::FIELD_FIO_F => $request->get('fio_f'),
              Questionaire::FIELD_FIO_I => $request->get('fio_i'),
              Questionaire::FIELD_FIO_O => $request->get('fio_o'),

              Questionaire::FIELD_COUNTRY =>  $countries,
              Questionaire::FIELD_BIRTHDAY => $request->get('birthday'),
              Questionaire::FIELD_GENDER => $request->get('gender'),
              
              Questionaire::FIELD_CITY_ID => $request->get('city_id'),
              Questionaire::FIELD_CITY_MATCH => $request->get('city_match'),

              Questionaire::FIELD_SPECIAL   => $specializations,           

              Questionaire::FIELD_SET_IS_COUCH => $request->get('set_is_couch') ?  1 : 0,
              Questionaire::FIELD_COUCH_HOURS => (!empty($request->get('couch_hours'))) ? $request->get('couch_hours') : 0,

              Questionaire::FIELD_EDUCATION        => $education,
              Questionaire::FIELD_TYPE_EDUCATION        => $type_education,

              Questionaire::FIELD_EXPERIENCES  => $experiences,
              Questionaire::FIELD_SKILLS  => $skills,

              Questionaire::FIELD_WORK_COMPANY  => $work_company,

              Questionaire::FIELD_EXPERIENCE_WORK_YEAR_ALL => $request->get('experience_work_year_all'),
              Questionaire::FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL   => $request->get('experience_work_base_year_all'),
              Questionaire::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE   => $request->get('count_work_people'),
              Questionaire::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL   => $request->get('count_work_people_all'),
  
              Questionaire::FIELD_LANGUAGES        => $languages,
              Questionaire::FIELD_LICENCES        => $licences,
              Questionaire::FIELD_TYPE_CAR        => $type_car,
              Questionaire::FIELD_TYPE_CONTRACT        => $type_contract,
              Questionaire::FIELD_EMPLOYMENT        => $employment,
              Questionaire::FIELD_WORK_TIME        => $work_time,

              /*Questionaire::FIELD_INCOME     => $income,*/
              Questionaire::FIELD_INCOME_OK     => $income_ok, 
              Questionaire::FIELD_INCOME_WANT     => $income_want, 
              Questionaire::FIELD_PERSONAL        => $personal,
              Questionaire::FIELD_RESUME     => $resume,
              Questionaire::FIELD_PHOTO        => $photo,  

              Questionaire::FIELD_RATE        => $status_rate,  
              Questionaire::FIELD_REASON        => $status_reason,  

              Questionaire::FIELD_CONTENT   => $content_json, 
              
          ];
  
          $questionaire = Questionaire::create($prepareData);         
  

          return response(['status' => $status, 'msg' => __('text.YouPassedQuestionnaire'), 'data' => $questionaire], 200);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question_item = Questionaire::find($id);

        $user = User::find(1);  
        $lang = 'ru';      
        if (!empty($user) && !empty($user->lang)) {
          $lang = $user->lang;
        }  
        
        $hide = ($lang === 'ru') ? 'Скрыто' : 'Hidden';


        if (!empty($question_item)) {

            //$opened = $question_item->opened;
            $opened = 1;
          
            //регион
            $question_item->region = City::get_region_text($question_item->city_id);

            //возраст 
            $age = "";
            $birthday = date('d.m.Y', strtotime($question_item->birthday));

            if (!empty($birthday)) {
               list($day,$month,$year) = explode(".",$birthday);
               $year_diff  = date("Y") - $year;
               $month_diff = date("m") - $month;
               $day_diff   = date("d") - $day;
               if ($day_diff < 0 && $month_diff==0){$year_diff--;}
               if ($day_diff < 0 && $month_diff < 0){$year_diff--;}
               $age = $year_diff; 
            } 
            $question_item->birthday = ($opened) ? $birthday  : $hide ;
            $question_item->age = ($opened) ? $age  : $hide ;


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

            return response(['data' => $question_item], 200);
        
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
        $work_id = (int) $request->get('work_id');
        $work = Work::find($work_id);
        $questionaire = Questionaire::find($id);
       
        if (empty($questionaire)) {
          return response(['status' => 0, 'msg' => __('text.NotFound')], 200);
        }

        $email = strtolower($request->get('email'));
        $phone = $request->get('phone');


        //проверка анкетирования 0 не прошел 1 прошел
        $status =  $request->get('status');
        
        $status_reason = NULL;
        
        if (isset($request->rate)) {
          $status_rate = intval($request->get('rate')); 
        } else {
          $status_rate = 0; 
        }

          //страны          
          if (!empty($request->get('countries'))) {
              $countries = $request->get('countries');
          } else {
            $countries = [];
          }
          $countries = json_encode($countries, JSON_UNESCAPED_UNICODE);



        //специализации
        $specializations = Specialization::implode_specializations($request->get('specializations')); 


          //образование          
          if (!empty($request->get('education'))) {
            $education = $request->get('education');
          } else {
            $education = [];
          }
          $education = json_encode($education, JSON_UNESCAPED_UNICODE);

          $type_education = [];
          if (!empty($request->get('type_education'))) {
            $type_education = $request->get('type_education');
          }
          $type_education = json_encode($type_education, JSON_UNESCAPED_UNICODE);



          //обязанности          
          if (!empty($request->get('experiences'))) {   
             $experiences = $request->get('experiences');   
          } else {
            $experiences = [];
          }
          $experiences = json_encode($experiences, JSON_UNESCAPED_UNICODE);


          //навыки
          $skills = [];
          $skill_levels = $request->get('skills');
          foreach ($skill_levels as $array_key => $array_value) {
              $array_item = [];
              $array_item["id"] = $array_key;
              $array_item["level"] = $array_value;
              array_push($skills, $array_item);
          }

          $skills = json_encode($skills, JSON_UNESCAPED_UNICODE);


          // Работа в компаниях          
          if (!empty($request->get('work_company'))) {
            $work_company = $request->get('work_company');
          } else {
            $work_company = [];
          }
          $work_company = json_encode($work_company, JSON_UNESCAPED_UNICODE);

 
  
          //языки   
           $languages = [];        
          if (!empty($request->get('languages'))) { 
            $languages_request = $request->get('languages');
            if (!empty($languages_request[0]['text'])) {
              $languages = $languages_request;
            }
          } else {           
          }
          $languages = json_encode($languages, JSON_UNESCAPED_UNICODE);




          //права
          $licences = [];
          if (!empty($request->get('licences'))) {            
            $licences_request = $request->get('licences');
            if (!empty($licences_request[0]['text'])) {
              $licences = $licences_request;
            } 
          } 
          $licences = json_encode($licences, JSON_UNESCAPED_UNICODE);


          //тип авто
          if (!empty($request->get('type_car'))) {
            $type_car = $request->get('type_car');
          } else {
            $type_car = [];
          }
          $type_car = json_encode($type_car, JSON_UNESCAPED_UNICODE);

          
          $type_contract = $request->get('type_contract_ok');          
    
          //Занятость
          $employment = !empty($request->get('employment_ok')) ? $request->get('employment_ok') : NULL; 

          //Режим работы
          $work_time = !empty($request->get('work_time_ok')) ? $request->get('work_time_ok') : NULL;

          //Личностные данные          
          if (!empty($request->get('personal'))) {
              $personal = $request->get('personal');
          } else {
            $personal = [];  
          }
          $personal = json_encode($personal, JSON_UNESCAPED_UNICODE); 

          
          /*$user_id = 1;*/

          //резюме
          $resume = !empty($request->get('resume')) ? $request->get('resume') : NULL;

          $photo = !empty($request->get('photo')) ? $request->get('photo') : NULL;


          //комментарии 
          $content = [];
          $content["expert_comment"] = "";
          $content["type_contract_comment"] = "";
          $content["employment_comment"] = "";
          $content["work_time_comment"] = "";
          $content_json = json_encode($content, JSON_UNESCAPED_UNICODE);


          //Текущий статус»: «Скачен с сорсинга» (по умолчанию)
          $status_now = !empty($request->get('status_now')) ? $request->get('status_now') : 0;               

          
          $income = !empty($request->get('income')) ? $request->get('income') : NULL;
          $income_want = !empty($request->get('income_want')) ? $request->get('income_want') : $work->income;
          $income_ok = $request->get('income_ok') ?  1 : 0;

                         
          if(isset($request->work_id)) {
          $questionaire->work_id = $work_id;  
          }       

          if(isset($request->opened)) {
          $questionaire->opened = $request->get('opened') ?  1 : 0;
          }

          if(isset($request->status)) {
          $questionaire->status = $status;
          }

          if(isset($request->status_now)) {
          $questionaire->status_now = $status_now; 
          }   

          if(isset($request->phone)) {
          $questionaire->phone = $request->get('phone');
          }

          if(isset($request->email)) {
          $questionaire->email = $email;
          }

          if(isset($request->fio_f)) {
          $questionaire->fio_f = $request->get('fio_f');
          }

          if(isset($request->fio_i)) {
          $questionaire->fio_i = $request->get('fio_i');
          }

          if(isset($request->fio_o)) {
          $questionaire->fio_o = $request->get('fio_o');
          }

          if(isset($request->countries)) {
          $questionaire->country =  $countries;
          }

          if(isset($request->birthday)) {
          $questionaire->birthday = $request->get('birthday');
          }

          if(isset($request->gender)) {
          $questionaire->gender  = $request->get('gender');
          }

          if(isset($request->city_id)) {
          $questionaire->city_id = $request->get('city_id');
          }

          if(isset($request->city_match)) {
          $questionaire->city_match = $request->get('city_match');
          }

          if(isset($request->specializations)) {
          $questionaire->special  = $specializations; 
          }          

          if(isset($request->set_is_couch)) {
          $questionaire->set_is_couch  = $request->get('set_is_couch') ?  1 : 0;
          }
          if(isset($request->couch_hours)) {  
          $questionaire->couch_hours= (!empty($request->get('couch_hours'))) ? $request->get('couch_hours') : 0;
          }

          if(isset($request->education)) {
          $questionaire->education  = $education;
          }
          if(isset($request->type_education)) {
          $questionaire->type_education = $type_education;
          }

          if(isset($request->experiences)) {
          $questionaire->experiences  = $experiences;
          }

          if(isset($request->skills)) {
          $questionaire->skills = $skills;
          }

          if(isset($request->work_company)) {
          $questionaire->work_company   = $work_company;
          }

          if(isset($request->experience_work_year_all)) {
          $questionaire->experience_work_year_all = $request->get('experience_work_year_all');
          }
          if(isset($request->experience_work_year_all)) {
          $questionaire->experience_work_base_year_all   = $request->get('experience_work_base_year_all');
          }
          if(isset($request->experience_work_year_all)) {
          $questionaire->count_work_people   = $request->get('count_work_people');
          }

          if(isset($request->experience_work_year_all)) {
          $questionaire->count_work_people_all   = $request->get('count_work_people_all');
          }

          if(isset($request->languages)) {
          $questionaire->languages   = $languages;
          }

          if(isset($request->licences)) {
          $questionaire->licences        = $licences;
          }

          if(isset($request->type_car)) {
          $questionaire->type_car       = $type_car;
          }

          if(isset($request->type_contract)) {
          $questionaire->type_contract    = $type_contract;
          }


          if(isset($request->employment)) {
          $questionaire->employment       = $employment;
          }

          if(isset($request->work_time)) {
          $questionaire->work_time       = $work_time;
          }

          if(isset($request->income)) {
          $questionaire->income      = $income;
          }

          if(isset($request->income_ok)) {
          $questionaire->income_ok   = $income_ok;
          }

          if(isset($request->income_want)) {
          $questionaire->income_want      = $income_want;
          }

          if(isset($request->personal)) {
          $questionaire->personal         = $personal;
          }

          if(isset($request->resume)) {
          $questionaire->resume  = $resume;
          }

          if(isset($request->photo)) {
          $questionaire->photo    = $photo;
          }

          if(isset($request->rate)) {
          $questionaire->rate = $status_rate;
          }

          if(isset($request->reason)) {
          $questionaire->reason = $status_reason;  
          }

          if(isset($request->content)) {
          $questionaire->content  = $content_json;
          }
              
          $questionaire->save();  

          return response(['status' => 1, 'msg' => __('text.Changed'), 'data' => $questionaire], 200);

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
        $work = Questionaire::find($itemId);
        if (!empty($work)) {               
            $work = Questionaire::destroy($itemId);
            return response(['status' => 1, 'msg' => __('text.Deleted')], 200);
        } else {
             return response(['status' => 0, 'msg' => __('text.NotFound')], 200);
        }
    }
}
