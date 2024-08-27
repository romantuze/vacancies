<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Degree;
use App\Models\GroupSpecialization;
use App\Models\Education;
use App\Models\TypeEducation;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Car;
use App\Models\CarLicence;
use App\Models\Employment;
use App\Models\Facilitation;
use App\Models\SkillLevel;
use App\Models\Currency;
use App\Models\TypeContract;
use App\Models\Personal;

class SelectController extends Controller
{
    public function index($lang)
    {
        $array = array(); 

        $array["degree"] = self::translate_select(Degree::all()->toArray(), $lang);

        $array["country"] = self::translate_select(Country::all()->toArray(), $lang);
        $array["group_specialization"] = self::translate_select(GroupSpecialization::all()->toArray(), $lang);

        $array["education"] = self::translate_select(Education::all()->toArray(), $lang);

        $array["type_education"] = self::translate_select(TypeEducation::all()->toArray(), $lang);
        $array["language"] = self::translate_select(Language::all()->toArray(), $lang);
        $array["language_level"] = self::translate_select(LanguageLevel::all()->toArray(), $lang);

        $array["car"] = self::translate_select(Car::all()->toArray(), $lang);
        $array["car_licence"] = self::translate_select(CarLicence::all()->toArray(), $lang);

        $array["employment"] = self::translate_select(Employment::all()->toArray(), $lang);
        $array["facilitation"] = self::translate_select(Facilitation::all()->toArray(), $lang);

        $array["skill_level"] = self::translate_select(SkillLevel::all()->toArray(), $lang);

        //$array["currency"] = Currency::all()->toArray();

        $array["type_contract"] = self::translate_select(TypeContract::all()->toArray(), $lang);

        $array["personal"] = self::translate_select(Personal::where('personal_id', NULL)->get()->toArray(), $lang);

        return response()->json($array);
    }

    public function translate_select($items_all, $lang) {
        $lang_text = "text";
        if ($lang === "en") {
            $lang_text = "text_en";
        }       
        $items_lang = array();
        foreach ($items_all as $all_item) {
            $item = array();
            $item["id"] = $all_item["id"];  
            $item["text"] = $all_item[$lang_text];       
            array_push($items_lang, $item);    
        }        
        return $items_lang;
    }
}
