<?php

namespace App\Http\Controllers;

use App\Repositories\WorksRepository;
use Illuminate\Http\Request;
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
use App\Models\Country;
use App\Models\User;
use App\Exports\WorksExport;
use App\Exports\QuestionairesExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice;

class ExportController extends Controller
{

  public function export_work($itemId) {
    $itemId = (int) $itemId;
    return Excel::download(new WorksExport($itemId), 'work'.$itemId.'.xlsx');
  }

  public function export_questionaire($itemId) {
    $itemId = (int) $itemId;
    return Excel::download(new QuestionairesExport($itemId), 'questionaire'.$itemId.'.xlsx');
  }


  public function export_questionaire_doc($itemId) {
  	$itemId = (int) $itemId;
  	$questionaire = Questionaire::find($itemId);

  	if (!empty($questionaire)) {
  		$size_title = 15;
        $format_title_header = array('name'=>'Arial','size' => 24,'bold' => true);
        $format_title = array('name'=>'Arial','size' => $size_title,'bold' => true);


    	$phpWord = new \PhpOffice\PhpWord\PhpWord();
       

        $questionaire_name = $questionaire->fio_f . " " . $questionaire->fio_i . " " . $questionaire->fio_o;

         $section = $phpWord->addSection();
        $text = $section->addText("Кандидат " . $questionaire_name,$format_title_header);
        $text = $section->addText("");

        if (!empty($questionaire->ref)) {
        $text = $section->addText("Ключ",$format_title);
        $text = $section->addText($questionaire->ref);  
        }

        $text = $section->addText("Результат анкеты",$format_title);
        $quetionaire_status = ($questionaire->status === 1) ? "Прошел" : "Не прошел";
        $text = $section->addText($quetionaire_status);  

        if ($questionaire->status === 0 && !empty($questionaire->reason)) {
            $questionaire_reason = json_decode($questionaire->reason);
         $questionaire_reason = implode(", ", $questionaire_reason);
        $text = $section->addText($questionaire_reason);  
        }


        $text = $section->addText("Телефон",$format_title);
        $text = $section->addText($questionaire->phone);  

        $text = $section->addText("Email",$format_title);
        $text = $section->addText($questionaire->email);  

        $text = $section->addText("Дата рождения",$format_title);
        $text = $section->addText($questionaire->birthday);  



        $gender = "-";
        if ($questionaire->gender === 1) {
            $gender = "Мужской";
        }
        if ($questionaire->gender === 2) {
            $gender = "Женский";
        }        
        $text = $section->addText("Пол",$format_title);
        $text = $section->addText($gender);  

        $city = "-";
        $region = "-";
        $country = "-";
        if (!empty($questionaire->city_id)) {
        $city = $questionaire->city_id;    
        $city_item = City::where('text',$city)->first();
        if (!empty($city_item)) {
        $region_id = $city_item->region_id;
        $region_item = Region::find($region_id);
        if (!empty($region_item)) {
        $region = $region_item->text;
        $country_id = $region_item->country_id;
        $country_item = Country::find($country_id);
        if (!empty($country_item)) {   
        $country = $country_item->text;
        }
        }
        }
        }
        $region_text = "Страна: ".$country.", "."Регион: ".$region.", "."Город: ".$city;
        $text = $section->addText($region_text ,$format_title);

        if (!empty($questionaire->city_match)) {
        	$text = $section->addText("Комментарий по локации",$format_title);
       		$text = $section->addText($questionaire->city_match);  
    	}

        $text = $section->addText("Специализация",$format_title);
        $specializations = Specialization::explode_specializations($questionaire->special);
        
        foreach($specializations as $item) {
             $text = $section->addText($item);
        }

        if ($questionaire->set_is_couch === 1) {
                $text = $section->addText("Коуч",$format_title);
                $text = $section->addText($questionaire->set_is_couch);  

                $text = $section->addText("Коуч часы",$format_title);        
                $text = $section->addText($questionaire->couch_hors);  
        }       

       
        $text = $section->addText("Образование",$format_title);
        $educations = json_decode($questionaire->education);
        foreach($educations as $item) {
             $text = $section->addText($item);
        }


        $text = $section->addText("Тип образования",$format_title);  
        $type_educations = json_decode($questionaire->type_education); 
        foreach($type_educations as $item) {
             $text = $section->addText($item);
        }


        $text = $section->addText("Стаж работы (в годах)",$format_title);        
       

        $text = $section->addText("Обязанности / Опыт",$format_title);     

        $experiences = json_decode($questionaire->experiences);
        foreach($experiences as $experience) {
        if (isset($experience->text) && isset($experience->ready) && isset($experience->year))   {
        $text = $section->addText($experience->text . " - ". $experience->ready . " " . $experience->year);    
        } 
        }
        $text = $section->addText("");

        $text = $section->addText("Уровень владения профессиональными навыками кандидата",$format_title); 
        $skills = json_decode($questionaire->skills);
        foreach($skills as $skill) {
             $text = $section->addText($skill->level);
        }

        $text = $section->addText("");

        $text = $section->addText("Уровень владения иностранным языком кандидата",$format_title); 
        $languages = json_decode($questionaire->languages);
        foreach($languages as $language) {
        $text = $section->addText($language->text . " " .$language->level);
        }

        $text = $section->addText("");  

        $type_contract = !empty($questionaire->type_contract) ? $questionaire->type_contract : "-";
        $text = $section->addText("Тип договора",$format_title);
        $text = $section->addText($type_contract);   

        $employment = !empty($questionaire->employment) ? $questionaire->employment : "-";
        $text = $section->addText("Занятость",$format_title); 
        $text = $section->addText($employment);   

        $work_time = !empty($questionaire->work_time) ? $questionaire->work_time : "-";
        $text = $section->addText("Режим работы",$format_title); 
        $text = $section->addText($work_time);  

        $text = $section->addText("Личностные данные кандидата",$format_title);
        $personal = json_decode($questionaire->personal);  
        foreach($personal as $item) {
             $text = $section->addText($item);
        }

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $project_name ="documents/candidate".$itemId.".docx";
        $objWriter->save(public_path($project_name));
       	return response()->download(public_path($project_name));

  	}


  }

	public function export_work_doc($itemId) {
		$itemId = (int) $itemId;

        $work = Work::find($itemId);     

        $user = User::find(1);  
        $lang = 'ru';      
        if (!empty($user) && !empty($user->lang)) {
            $lang = $user->lang;
        }

        if (!empty($work)) {
           
            $size_title = 14;
            $format_title_header = array('name'=>'Arial','size' => 24,'bold' => true);
            $format_title = array('name'=>'Arial','size' => $size_title,'bold' => true);
            
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            $title_vacancy = "Наименование вакансии: ";
            if ($lang === 'en') { $title_vacancy = "Name of the vacancy: "; }

            $title_salary = "Фот - ";
            if ($lang === 'en') {  $title_salary = "Salary - "; }

            $title_accrued = "начисляемая до вычета налогов";
            if ($lang === 'en') {  $title_accrued = "accrued before taxes"; }

            $title_agreement = "По договоренности";
            if ($lang === 'en') {  $title_agreement = "By agreement"; }     

            $title_company = "Компания";
            if ($lang === 'en') { $title_company = "Company"; }     

            $title_text_company =  " – одна из лидеров в своей отрасли проводит первичное он-лайн анкетирование. На указанный в отклике контакт Вам будет выслана ссылка на сайт. Преимущества анкетирования на сайте:";
            if ($lang === 'en') {
                $title_text_company =  " – one of the leaders in its industry conducts testing when recruiting for a job opening.";

                $title_text_company .=  " For the initial interview, please go to the Questionnaire, following the link that will be sent to the contacts you specified.";
            }

            $text = $section->addText($title_vacancy . $work->name,$format_title_header);
            $text = $section->addText("");

            $income_title = $title_salary;

            if ($work->publish_income === 1 && isset($work->income)) {
                    $income_title .= $work->income . ", " . $title_accrued;
            } else {
                    $income_title .= $title_agreement;
            }
            $text = $section->addText($income_title,$format_title);
                      
                  $company_id = $work->user_id;

            $company_item = User::find($company_id);
            if (isset($company_item) && !empty($company_item->name)) {
                $company_name = $company_item->name;
            }
            $company_name = $company_name;

            $text = $section->addText($title_company . " " . $company_name . $title_text_company, $format_title);

            if ($lang !== 'en') {
                $text = $section->addText("- Есть время подумать и корректно заполнить ответ на вопросы");
                $text = $section->addText("- Не надо приезжать в офис работодателя");
                $text = $section->addText("- Результат узнаете сразу, никаких «Мы Вам перезвоним!»");
            }

            $text = $section->addText("");

            $city = "-";
            $region = "-";
            $country = "-";
            if (!empty($work->city_id)) {
            $city = $work->city_id;    
            $city_item = City::where('text',$city)->first();
            if (!empty($city_item)) {
            $region_id = $city_item->region_id;
            $region_item = Region::find($region_id);
            if (!empty($region_item)) {
            $region = $region_item->text;
            $country_id = $region_item->country_id;
            $country_item = Country::find($country_id);
            if (!empty($country_item)) {   
            $country = $country_item->text;
            }
            }
            }
            }


            $title_country = "Страна";
            if ($lang === 'en') { $title_country = "Country"; }    

            $title_region = "Регион";
            if ($lang === 'en') { $title_region = "Region"; }  

            $title_city = "Город";
            if ($lang === 'en') { $title_city = "City"; } 

            $region_text = $title_country . ": " . $country . "," . $title_region . ": " . $region . ", " . $title_city . ": ". $city;
            $text = $section->addText($region_text ,$format_title);

            $text = $section->addText("");

            $title_experience_work_year_all = "Общий требуемый стаж в годах";
            if ($lang === 'en') { $title_experience_work_year_all = "Total work experience (in years)"; } 

            $title_experience_work_base_year_all = "Управленческий стаж в годах";
            if ($lang === 'en') { $title_experience_work_base_year_all = "Managing experience (in years)"; } 

            $experience_work_year_all = $work->experience_work_year_all;
            $text = $section->addText($title_experience_work_year_all . ": ".$experience_work_year_all,$format_title);  

            $experience_work_base_year_all = $work->experience_work_base_year_all;
            if (!empty($experience_work_base_year_all)) { 
                $text = $section->addText($title_experience_work_base_year_all .": ".$experience_work_base_year_all,$format_title); 
            }

            $text = $section->addText("");
            $title_education = "Уровень и тип образования";
            if ($lang === 'en') { $title_education = "Educational level and Type"; }
            $text = $section->addText($title_education,$format_title);
            $education = !empty($work->education) ? $work->education : "-";
            $text = $section->addText($education);
            $type_education = !empty($work->type_education) ? $work->type_education : "-";
            $text = $section->addText($type_education);
            $text = $section->addText("");

            $title_employment = "Занятость";
            if ($lang === 'en') { $title_employment = "Type of employment"; }
            $employment_array = json_decode($work->employment);
            if (!empty($employment_array)) {
            $text = $section->addText($title_employment,$format_title);
            $employment = "-";
            $employment_new = [];
            foreach ($employment_array as $item) {
            if (!empty($item)) {
            array_push($employment_new, $item);
            }
            }
            if (!empty($employment_new)) {
            $employment = implode(", ", $employment_new);
            }
            $text = $section->addText($employment);
            $text = $section->addText("");
            }

            $title_type_contract = "Тип договора";
            if ($lang === 'en') { $title_type_contract = "Employment contract type"; }
            $text = $section->addText($title_type_contract,$format_title);
            $type_contract_text = "-";
            $type_contract  = json_decode($work->type_contract);
            $type_contract_new = [];
            foreach ($type_contract as $item) {
            if (!empty($item->text)) { array_push($type_contract_new, $item->text); }
            }
            if (!empty($type_contract_new)) {
            $type_contract_text = implode(", ", $type_contract_new);  
            }     
            $text = $section->addText($type_contract_text);
            $text = $section->addText("");

            $title_work_time = "Режим работы";
            if ($lang === 'en') { $title_work_time = "Working schedule"; }
            $work_time = json_decode($work->work_time);
            if (!empty($work_time)) {
            $text = $section->addText($title_work_time,$format_title);
            $work_time_text = "-";
            if(!empty($work_time)) {
                $work_time_text = implode(", ", $work_time);  
            }
             $text = $section->addText($work_time_text);
             $text = $section->addText("");
            }

            $title_experiences = "Выполняемые Обязанности";
            if ($lang === 'en') { $title_experiences = "Responsibilities"; }
            $text = $section->addText($title_experiences,$format_title);
            $experiences = json_decode($work->experiences);
            foreach($experiences as $experience) {
            	 $text = $section->addText($experience->text);
            }
            $text = $section->addText("");

            $title_skills = "Требуемые навыки";
            if ($lang === 'en') { $title_skills = "Hard skills"; }
            $text = $section->addText($title_skills,$format_title);
            $skills = json_decode($work->skills);
            foreach($skills as $skill) {
            	 $text = $section->addText($skill->text);
            }


            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $project_name ="documents/vacancy".$itemId.".docx";
            $objWriter->save(public_path($project_name));
           	return response()->download(public_path($project_name));

        }


	}
}
