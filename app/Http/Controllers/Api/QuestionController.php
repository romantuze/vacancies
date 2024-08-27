<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CompanyController;
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

use Validator;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;



class QuestionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($workId)
    {
        $workId = (int) $workId;
        $questions_items = Questionaire::where("work_id", $workId)->whereIn("status_now",[1,3])->orderBy('rate', 'DESC')->get();

        $user = User::find(1);  
        $lang = 'ru';      
        if (!empty($user) && !empty($user->lang)) {
          $lang = $user->lang;
        }  
        
        $hide = ($lang === 'ru') ? 'Скрыто' : 'Hidden';

         foreach ($questions_items as $question_item) {

            $opened = $question_item->opened;

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

        return response()->json($questions_items->toArray());
    }

    public function admin_index($workId) 
    {
        $workId = (int) $workId;
        $questions_items = Questionaire::where("work_id", $workId)->where("status",1)->get();

         foreach ($questions_items as $question_item) {
            $opened = 1;
            $question_item->photo = FileUpload::get_photo_url($question_item->photo, $opened);
            $question_item->resume = FileUpload::get_resume_url($question_item->resume, $opened);
         }

        return response()->json($questions_items->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       

      $validator =  Validator::make($request->all(), [
       'resume' => 'required|mimes:jpg,jpeg,png,tiff,bmp,pdf,doc,docx,rtf,txt|max:4096',
       'photo' => 'required|mimes:jpg,jpeg,png,tiff,bmp|max:2048'
      ]);

      if ($validator->fails()) {
           return response(['status' => 3, 'msg' => __('text.ErrorFileUpload')], 200);
      } 

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
        $status = 1;
        $status_percent = 100;
        $status_data = self::check($request, $work);   
        $status = $status_data["status"];
        $status_reason = $status_data["status_reason"]; 
        $status_rate = $status_data["status_rate"]; 
        


          //страны          
          if (!empty($request->get('countries'))) {
              $countries = json_decode($request->get('countries'));
          } else {
            $countries = [];
          }
          $countries = json_encode($countries, JSON_UNESCAPED_UNICODE);



        //специализации
        $specializations_json = json_decode($request->get('specializations'));
        $specializations = Specialization::implode_questionaire_specializations($specializations_json); 


          //образование          
          if (!empty($request->get('education'))) {
            $education = json_decode($request->get('education'));
          } else {
            $education = [];
          }
          $education = json_encode($education, JSON_UNESCAPED_UNICODE);

          $type_education = [];
          if (!empty($request->get('type_education'))) {
            $type_education = json_decode($request->get('type_education'));
          }
          $type_education = json_encode($type_education, JSON_UNESCAPED_UNICODE);



          //обязанности          
          if (!empty($request->get('experiences'))) {   
             $experiences = json_decode($request->get('experiences'));   
          } else {
            $experiences = [];
          }
          $experiences = json_encode($experiences, JSON_UNESCAPED_UNICODE);


          //навыки
          $skills = [];
          $skill_levels = json_decode($request->get('skills'));
          foreach ($skill_levels as $array_key => $array_value) {
              $array_item = [];
              $array_item["id"] = $array_key;
              $array_item["level"] = $array_value;
              array_push($skills, $array_item);
          }

          $skills = json_encode($skills, JSON_UNESCAPED_UNICODE);


          // Работа в компаниях
          
          if (!empty($request->get('work_company'))) {
            $work_company = json_decode($request->get('work_company'));
          } else {
            $work_company = [];
          }
          $work_company = json_encode($work_company, JSON_UNESCAPED_UNICODE);

 
  
          //языки          
          if (!empty($request->get('languages'))) { 
            $languages_request = json_decode($request->get('languages'));
            if (!empty($languages_request[0]->text)) {
              $languages = $languages_request;
            }
          } else {
            $languages = []; 
          }
          $languages = json_encode($languages, JSON_UNESCAPED_UNICODE);


          //права
          $licences = [];
          if (!empty($request->get('licences'))) {            
            $licences_request = json_decode($request->get('licences'));
            if (!empty($licences_request[0]->text)) {
              $licences = $licences_request;
            } 
          } 
          $licences = json_encode($licences, JSON_UNESCAPED_UNICODE);


          //тип авто
          if (!empty($request->get('type_car'))) {
            $type_car = json_decode($request->get('type_car'));
          } else {
            $type_car = [];
          }
          $type_car = json_encode($type_car, JSON_UNESCAPED_UNICODE);

          $type_contract = $request->get('type_contract_ok');      

          $employment = !empty($request->get('employment_ok')) ? $request->get('employment_ok') : NULL; 

          $work_time = !empty($request->get('work_time_ok')) ? $request->get('work_time_ok') : NULL;

         
          if (!empty($request->get('personal'))) {
              $personal = json_decode($request->get('personal'));
          } else {
            $personal = [];  
          }
          $personal = json_encode($personal, JSON_UNESCAPED_UNICODE); 

          
          $user_id = 1;

          //резюме
          $resume ="";
          if($request->file('resume')) {
             $file_resume_name = time().'.'.$request->resume->getClientOriginalExtension();
             $file_resume_path = $request->file('resume')->storeAs('uploads/resume', $file_resume_name, 'public');
             if ($file_resume_path) {
              $fileUpload = new FileUpload;
              $fileUpload->user_id = $user_id;
              $fileUpload->name = $file_resume_name;
              $fileUpload->path = $file_resume_path;
              $fileUpload->save();
              $resume = $file_resume_name;
             }
          }

          $photo = "";
          if($request->file('photo')) {
             $file_photo_name = time().'.'.$request->photo->getClientOriginalExtension();
             $file_photo_path = $request->file('photo')->storeAs('uploads/photo', $file_photo_name, 'public');
             if ($file_photo_path) {
              $fileUpload = new FileUpload;
              $fileUpload->user_id = $user_id;
              $fileUpload->name = $file_photo_name;
              $fileUpload->path = $file_photo_path;
              $fileUpload->save();
              $photo = $file_photo_name;
             }             
          }

          //комментарии 
          $content = [];
          $content["expert_comment"] = "";
          $content["type_contract_comment"] = "";
          $content["employment_comment"] = "";
          $content["work_time_comment"] = "";

          $content_json = json_encode($content, JSON_UNESCAPED_UNICODE);



          //Текущий статус»: «Скачен с сорсинга» (по умолчанию)
          $status_now = 0;

          //желаемый доход         
          $income_ok = $request->get('income_ok') ?  1 : 0;

          if ($income_ok === 1) {
            $income_want = !empty($work->income_start) ? $work->income_start : NULL ;
          } else {
            $income_want = !empty($request->get('income_want')) ? $request->get('income_want') : NULL;  
          }        

          $birthday = $request->get('birthday');
          if (!empty( $birthday)) { $birthday = date('Y-m-d', strtotime($birthday)); }

          $ref = NULL;
          if (!empty($request->ref)) {
            $ref = $request->ref;
          }
      
          $prepareData = [               
         
              Questionaire::FIELD_WORK_ID => $work_id,  

              Questionaire::FIELD_REF => $ref,  

              Questionaire::FIELD_OPENED => 0,     

              Questionaire::FIELD_STATUS => $status,
              Questionaire::FIELD_STATUS_NOW => $status_now,    
  
              Questionaire::FIELD_PHONE => $request->get('phone'),  
              Questionaire::FIELD_EMAIL => $email,  

              Questionaire::FIELD_FIO_F => $request->get('fio_f'),
              Questionaire::FIELD_FIO_I => $request->get('fio_i'),
              Questionaire::FIELD_FIO_O => $request->get('fio_o'),

              Questionaire::FIELD_COUNTRY =>  $countries,
              Questionaire::FIELD_BIRTHDAY => $birthday,
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
  
          if ($status === 1) {
            return response(['status' => $status, 'msg' => __('text.YouPassedQuestionnaire'), 'data' => $questionaire], 200);
          } else {
            return response(['status' => $status, 'msg' => __('text.DoNotAllowEmployer'), 'data' => $questionaire], 200);
          }  
    }

    
    public function check_user(Request $request) {
      $work_id = (int) $request->get('work_id');  
      $email = strtolower($request->get('email'));
      $phone = $request->get('phone');
      if (($work_id > 0) && !empty($email) && !empty($phone)) {
      $work = Work::find($work_id);
      $questionaire = Questionaire::where('work_id', $work_id)->where('email', $email)->where('phone',$phone)->first();
      if (!empty($questionaire)) {
        return response(['status' => 2, 'msg' => __('text.ApplicationAlreadySubmitted')], 200);
      }
      return response(['status' => 1, 'msg' => 'Ok'], 200);
      }
      return response(['status' => 0, 'msg' => __('text.Error')], 200);
    }

    public function open(Request $request) {
      $questionaire_id = (int) $request->get('questionaire_id');       
      $work_id = (int) $request->get('work_id');
      $work = Work::find($work_id);
      $work_company_id = $work->user_id;
      $company = User::find($work_company_id);
      $questionaire = Questionaire::find($questionaire_id);
      $sum_open = 0;

      if (!empty($questionaire) && !empty($work) && !empty($company)) {  
         $work_income = intval($work->income);
         $sum_open = Work::get_sum_open($work->income, $work->sale);
         $balance = $company->balance;

         if ($balance >= $sum_open) {
            $balance_new = $balance - $sum_open;
            $company->balance = $balance_new;
            $company->save();

            $work_balance_new = $work->balance + $sum_open;
            $work->balance = $work_balance_new;
            $work->save();

            $questionaire->status_now = 3;
            $questionaire->opened = 1;
            $questionaire->save();
            return response(['status' => 1, 'msg' => __('text.CandidateDataIsOpen')], 200);
          } else {
            return response(['status' => 0, 'msg' => __('text.InsufficientFundsInPersonalAccount')], 200);
          }   
      }
    }

    public function replace(Request $request) {
      $questionaire_id = (int) $request->get('questionaire_id');       
      $work_id = (int) $request->get('work_id');
      $work = Work::find($work_id);
      $work_company_id = $work->user_id;
      $company = User::find($work_company_id);
      $questionaire = Questionaire::find($questionaire_id);
      $sum_replace = 0;
      if (!empty($questionaire) && !empty($work) && !empty($company)) {  
         $work_income = intval($work->income);
         $sum_replace = Work::get_sum_replace($work->income, $work->sale); 
         
         $balance = $company->balance;

         if ($balance >= $sum_replace) {
            $balance_new = $balance - $sum_replace;
            $company->balance = $balance_new;
            $company->save();

            $work_balance_new = $work->balance + $sum_replace;
            $work->balance = $work_balance_new;
            $work->save();
            
            $questionaire->status_now = 2;
            $questionaire->opened = 0;
            $questionaire->save();
            return response(['status' => 1, 'msg' => __('text.CandidateReplaced')], 200);
          } else {
            return response(['status' => 0, 'msg' => __('text.InsufficientFundsInPersonalAccount')], 200);
          }   
      }
    }


    public function show(Questionaire $questionaire)
    {        
    }

    public function status_now(Request $request)
    {
      $questionaire_id = (int) $request->get('questionaire_id');
      $new_status = (int) $request->get('new_status');
      $user_id = (int) $request->get('user_id');
       if ($questionaire_id > 0 && $new_status > 0 && $user_id > 0) {
          $questionaire = Questionaire::find($questionaire_id);
          if (!empty($questionaire)) {
            $questionaire->status_now = $new_status;
            $questionaire->save();           
            return response(['status' => 1, 'msg' => __('text.StatusChanged')], 200);
          }
       }
    }

    public function update_comment(Request $request)
    {
       $questionaire_id = (int) $request->get('questionaire_id');      

       $questionaire = Questionaire::find($questionaire_id);
       if (!empty($questionaire)) {
          $expert_comment = $request->get('expert_comment') !== NULL ? $request->get('expert_comment') : "" ;
          $type_contract_comment = $request->get('type_contract_comment') !== NULL ? $request->get('type_contract_comment') : "" ;
          $employment_comment = $request->get('employment_comment') !== NULL ? $request->get('employment_comment') : "" ;
          $work_time_comment = $request->get('work_time_comment') !== NULL ? $request->get('work_time_comment') : "" ;
          $content = [];
          $content["expert_comment"] = $expert_comment;
          $content["type_contract_comment"] = $type_contract_comment;
          $content["employment_comment"] = $employment_comment;
          $content["work_time_comment"] = $work_time_comment;
          $content_json = json_encode($content, JSON_UNESCAPED_UNICODE);
          $questionaire->content = $content_json;
          $questionaire->save();    
          return response(['status' => 1, 'msg' => __('text.SavedComments')], 200);
       }     
    }

    public function check($request, $work)
    {
        $status = 1;
       
        $status_percent = 0;
        $status_msg = "";
        $status_array = [];

        $candidate_gender = (int) $request->get('gender');
        $work_gender = $work->gender;
        if ($work_gender !== NULL) {
          if($candidate_gender !== $work_gender) {
            $status = 0;
        
            $status_msg = "Пол не соответствует";
            array_push($status_array,  $status_msg);
          }
        }

        $age = "";
        $birthday = date('d.m.Y', strtotime($request->birthday));
        if (!empty($birthday)) {
           list($day,$month,$year) = explode(".",$birthday);
           $year_diff  = date("Y") - $year;
           $month_diff = date("m") - $month;
           $day_diff   = date("d") - $day;
           if ($day_diff < 0 && $month_diff==0){$year_diff--;}
           if ($day_diff < 0 && $month_diff < 0){$year_diff--;}
           $age = $year_diff; 
             if (!empty($work->age_start) && !empty($work->age_end)) {
                $age_start = (int) $work->age_start;
                $age_start = $age_start - 2;
                $age_end = (int) $work->age_end;
                $age_end = $age_end + 2;
                if (($age < $age_start) || ($age > $age_end)) {
                    $status = 0;
                    
                    $status_msg = "Возраст не соответствует";
                    array_push($status_array,  $status_msg);
                }
             }

             if (!empty($work->age_start) && !empty($work->age_end)) {
               $age_start = (int) $work->age_start;
               $age_end = (int) $work->age_end;
              $age_average = intval(($age_end - $age_start) / 2);
              if ($age === $age_average) {
                $status_percent = $status_percent + 10;
              }
              if ($age > $age_average) {
                $age_dif = intval($age - $age_average);
                $age_dif = intval(0.1 * $age_dif);
                $status_percent = $status_percent - $age_dif;
              }
              if ($age < $age_average) {
                $age_dif = intval($age_average - $age);
                $age_dif = intval(0.1 * $age_dif);
                $status_percent = $status_percent - $age_dif;
              }
             }  
        }           



        $city_match = $request->city_match;
          if (empty($city_match)) {
             $status_percent = $status_percent + 10;
          }
         if (!empty($city_match)) {
             if (($city_match === "Локация не устраивает" || $city_match === "Location does not suit")) {
                $status = 0;
              
                $status_msg = "Локация не соответствует";
                array_push($status_array,  $status_msg);
             }
         }




        $specializations_candidate = [];
        if (!empty($request->get('specializations'))) {
         $specializations_candidate = json_decode($request->get('specializations')); 
        }
        $specializations_work = [];
        if (!empty($work->special)) {
            $specializations_work = Specialization::explode_specializations($work->special);
        }

        if (!empty($work->is_view_special) && ($work->is_view_special === 1)) {           
            $check_spec = false;
            foreach ($specializations_work as $specializations_work_key => $specializations_work_value) {
                if (in_array($specializations_work_value, $specializations_candidate)) {
                    $check_spec = true;
                }
            }
            if (!$check_spec) {
                $status = 0;
               
                $status_msg = "Специализация не соответствует";
                array_push($status_array,  $status_msg);
            }
        }

        //рейтинг
        $array_specializations = [];
        $array_agroup_specializations = [];
       
        foreach ($specializations_candidate as $specializations_candidate_value) {
          if (!empty($specializations_candidate_value->text)) { array_push($array_specializations, $specializations_candidate_value->text); }
          if (!empty($specializations_candidate_value->text)) { array_push($array_agroup_specializations, $specializations_candidate_value->group); }
        }
        $specializations_work = [];
        if (!empty($work->special)) {
               $specializations_work = Specialization::explode_specializations($work->special);
        }
        foreach ($specializations_work as $specializations_work_value) {
          if (in_array($specializations_work_value, $array_specializations)) {
                    $check_spec = true;
                    $status_percent = $status_percent + 10; //при совпадении хотя бы одной специализаций – прибавляется еще 1
                    break;
          }
        }



       
      
        $work_education_text = $work->education;
        $work_type_education_text = $work->type_education;
        $work_education_item = Education::where('text', $work_education_text)->first();
        $work_type_education_item = TypeEducation::where('text', $work_type_education_text)->first();
        $work_education_id = !(empty($work_education_item)) ? $work_education_item->id : NULL;
        $work_type_education_id = !(empty($work_type_education_item)) ? $work_type_education_item->id : NULL;

        $candidate_education_arr_text = json_decode($request->get('education'));
        $candidate_type_education_arr_text = json_decode($request->get('type_education'));
        $candidate_education_arr_id = [];
        foreach ($candidate_education_arr_text as $candidate_education_arr_item) {        
          $candidate_education_item = Education::where('text', $candidate_education_arr_item)->first();
          if (!empty($candidate_education_item)) {
            $candidate_education_id = $candidate_education_item->id;
            if (!empty($candidate_education_id)) { array_push($candidate_education_arr_id, $candidate_education_id); }
          }
        }

        if (!empty($candidate_education_arr_id)) {
            rsort($candidate_education_arr_id);
            if($candidate_education_arr_id[0] <  $work_education_id) {
                $status = 0;
                
                $status_msg = "Уровень образования не соответствует";
                array_push($status_array,  $status_msg);
            }
        }
        //рейтинг
        if (in_array($work_education_text, $candidate_education_arr_text)) {            
           $status_percent = $status_percent + 10;                   
        }
        if (in_array($work_type_education_text, $candidate_type_education_arr_text)) {            
           $status_percent = $status_percent + 10;                   
        }




$work_experiences =  json_decode($work->experiences);
$candidate_experiences = json_decode($request->get('experiences'));
foreach($candidate_experiences as $candidate_key => $candidate_experience) {    

    if ($candidate_experience->yes_no == 0 || $candidate_experience->ready === "Думаю, что готов выполнить данные обязанности" || $candidate_experience->ready === "I think I am ready to perform these duties") {
         $status = 0;
         
         $status_msg = "Обязанности не соответствует";
         array_push($status_array,  $status_msg);
    } else {
     
    //Количество лет опыта работы 
      $work_experience = $work_experiences[$candidate_key];
      $candidate_experience_year = isset($candidate_experience->year) ? $candidate_experience->year : 0;
      $candidate_experience_year_new = $candidate_experience_year * 1.21;
      $work_experience_year = isset($work_experience->year) ? $work_experience->year : 0;

      if ($work_experience_year > 0) {
        if ($work_experience_year > $candidate_experience_year_new) {
         $status = 0;         
         $status_msg = "Количество лет опыта работы недостаточно";
         array_push($status_array,  $status_msg);
        }
      }
    }
}

//рейтинг
foreach($candidate_experiences as $candidate_key => $candidate_experience) {
  $work_experience = $work_experiences[$candidate_key];
  $candidate_experience_year = isset($candidate_experience->year) ? $candidate_experience->year : 0;
      $work_experience_year = isset($work_experience->year) ? $work_experience->year : 0;
      if ($work_experience_year > 0 && $candidate_experience_year > 0) {
        if ($work_experience_year == $candidate_experience_year) {
          $status_percent = $status_percent + 10;
        }
        if ($candidate_experience_year > $work_experience_year) {
           $status_percent = $status_percent + 10;
          $year_dif = intval($candidate_experience_year - $work_experience_year);
           $status_percent = $status_percent - ($year_dif * 1);
        }
      }
}



$work_skills =  json_decode($work->skills);
$candidate_skills = json_decode($request->get('skills'));

foreach($work_skills as $work_skill_key => $work_skill_value) {   
    if (isset($work_skill_value->level) && isset($candidate_skills[$work_skill_key])) {

        $work_level_text = $work_skill_value->level;
        $work_level = SkillLevel::where('text', $work_level_text)->first();
        $work_level_id = isset($work_level->id) ? $work_level->id : 0;

        $candidate_level_text = $candidate_skills[$work_skill_key];
        $candidate_level = SkillLevel::where('text', $candidate_level_text)->first();
        $candidate_level_id = isset($candidate_level->id) ? $candidate_level->id : 0;  
               
        if ($candidate_level_id < $work_level_id) {
            $status = 0;
            
            $status_msg = "Уровень владения профессиональными навыками не соответствует";
            array_push($status_array,  $status_msg);
        }
    }
}





$work_experience_work_year_all = $work->experience_work_year_all;
$work_experience_work_base_year_all = $work->experience_work_base_year_all;
$work_count_work_people = $work->count_work_people;
$work_count_work_people_all = $work->count_work_people_all;

$candidate_experience_work_year_all  = (int) $request->get('experience_work_year_all');
$candidate_experience_work_year_all  = 1.21 * $candidate_experience_work_year_all;
$candidate_experience_work_base_year_all  = (int) $request->get('experience_work_base_year_all');
$candidate_experience_work_base_year_all  = 1.21 * $candidate_experience_work_base_year_all;
$candidatek_count_work_people  = (int) $request->get('count_work_people');
$candidate_count_work_people_all  = (int) $request->get('count_work_people_all');

if ($candidate_experience_work_year_all < $work_experience_work_year_all) {
    $status = 0;
   
    $status_msg = "Общий профессиональный стаж не соответствует";
    array_push($status_array,  $status_msg);
}
if ($candidate_experience_work_base_year_all < $work_experience_work_base_year_all) {
    $status = 0;
   
    $status_msg = "Общий управленческий стаж не соответствует";
    array_push($status_array,  $status_msg);
}



$work_languages = json_decode($work->languages);
$candidate_languages = json_decode($request->get('languages'));

foreach($work_languages as $work_languages_key => $work_languages_value) {
  $work_degree = $work_languages_value->degree;
  if (!empty($work_degree) && $work_degree === 2) {
      if (empty($candidate_languages)) {
        $status = 0;
      
        $status_msg = "Владение иностранным языком отсутствует";
        array_push($status_array,  $status_msg);
        break;   
      }
  }
}

foreach($work_languages as $work_languages_key => $work_languages_value) {
  $work_degree = $work_languages_value->degree;
  $work_text = $work_languages_value->text;
  $work_level = $work_languages_value->level;
  if (!empty($work_degree) && $work_degree === 2) {

  }
}



$work_licences = json_decode($work->licences);
$candidate_licences = json_decode($request->get('licences'));
foreach($work_licences as $work_licences_key => $work_licences_value) {
  $work_licences_text = isset($work_licences_value->text) ? $work_licences_value->text  : NULL;
  $work_licences_degree = isset($work_licences_value->degree) ? $work_licences_value->degree  : NULL;
  if ($work_licences_degree === 2) {
      // Отсутствие прав
      if (empty($candidate_licences)) {
          $status = 0;
         
          $status_msg = "Наличие прав управления автомобилем является обязательным требованием";
          array_push($status_array,  $status_msg);
          break;
      }
      $candidate_has_licences = false;
      foreach ($candidate_licences as $candidate_licences_key => $candidate_licences_value) {
        $candidate_licences_text = isset($candidate_licences_value->text) ? $candidate_licences_value->text  : NULL;    
        if ($candidate_licences_text === $work_licences_text) {
          $candidate_has_licences = true;
        }
      }
      if ($candidate_has_licences === false) {
         $status = 0;
         
         $status_msg = "Права управления автомобилем не соответствуют";
         array_push($status_array,  $status_msg);
        break;
      }

  }
}



$work_type_car = json_decode($work->type_car);
$candidate_type_car = json_decode($request->get('type_car'));
if (!empty($work_type_car)) {
  $work_type_car_text = isset($work_type_car[0]->text) ? $work_type_car[0]->text  : NULL;
  $work_type_car_degree =  isset($work_type_car[0]->degree) ? $work_type_car[0]->degree : NULL;
  if ($work_type_car_degree === 2) {
    if (empty($candidate_type_car)) {
        $status = 0;
        
        $status_msg = "Наличие автомобиля является обязательным требованием";
        array_push($status_array,  $status_msg);
    }
  }
}




$type_contract_ok = $request->get('type_contract_ok');
if ($type_contract_ok === "Нет" || $type_contract_ok === "No") {
    $status = 0;
    
    $status_msg = "Тип договора не соответствует";
    array_push($status_array,  $status_msg);
}


$employment_ok = $request->get('employment_ok');
if ($employment_ok === "Нет" || $employment_ok === "No") {
    $status = 0;
    
    $status_msg = "Занятость не соответствует";
    array_push($status_array,  $status_msg);
}


$work_time_ok = $request->get('work_time_ok');
if ($work_time_ok === "Нет" || $work_time_ok === "No") {
    $status = 0;
    
    $status_msg = "Режим работы не соответствует";
    array_push($status_array,  $status_msg);
}





$work_average_income = 0;
if (!empty($work->income_start) && !empty($work->income_end)) {
$work_average_income = intval((intval($work->income_start) + intval($work->income_end)) / 2);
}


$work_income = $work->income;
$work_income_new = 1.05 * $work->income;
$work_income_new = intval($work_income_new);
$income_want = (int) $request->get('income_want');
$income_ok = (int) $request->get('income_ok');
if ($income_ok === 0) {
    if ($income_want > $work_income_new) {
        $status = 0;        
        $status_msg = "Доход не соответствует";
        array_push($status_array,  $status_msg);
    }
}


if ($income_ok === 1) {
$status_percent = $status_percent + 100;
}
if ($income_ok === 0 && $income_want > 0 && $work_average_income > 0) {
  $income_dif = intval($income_want - $work_average_income);
  $income_dif = intval($income_dif * 0.003);
  $status_percent = $status_percent - $income_dif;
}




$work_personal = json_decode($work->personal);
$candidate_personal = json_decode($request->get('personal'));

if (!empty($work_personal) && !empty($candidate_personal)) {

foreach ($work_personal as $work_personal_value) {
  if (in_array($work_personal_value, $candidate_personal)) {
    $status_percent = $status_percent + 7;
  }
}

}

        if ($status === 0) {
          $status_percent = 0;
        }

        $status_data = [];
        $status_data['status'] = $status;
        $status_data['status_reason'] = json_encode($status_array, JSON_UNESCAPED_UNICODE);
        $status_data['status_rate'] = $status_percent;

        return $status_data;

    }

}