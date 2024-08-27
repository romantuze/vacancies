<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//таблица языки
class Language extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'text',
        'text_en'
    ];


    public static function language_encode($language_request)
    {
    	$languages = [];          
        $languages_request = $language_request;
        foreach($languages_request as $languages_key => $languages_value) {
            if (!empty($languages_request[$languages_key]['text']) && !empty($languages_request[$languages_key]['level'])) {
                 array_push($languages, $languages_value);
            }               
        }      
        $languages = json_encode($languages, JSON_UNESCAPED_UNICODE);
        return $languages;
    }
}
