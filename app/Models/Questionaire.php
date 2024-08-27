<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionaire extends Model
{
    use HasFactory;

    CONST FIELD_ID = 'id',

        FIELD_WORK_ID = 'work_id',

        FIELD_REF = 'ref', 

        FIELD_OPENED = 'opened',   

        FIELD_STATUS = 'status',   

        FIELD_STATUS_NOW = 'status_now', 

        FIELD_PHONE = 'phone',   

        FIELD_EMAIL = 'email', 

        FIELD_FIO_F = 'fio_f', 
        FIELD_FIO_I = 'fio_i', 
        FIELD_FIO_O = 'fio_o', 

        FIELD_COUNTRY = 'country', 
        FIELD_BIRTHDAY = 'birthday', 
        FIELD_GENDER = 'gender', 
        
        FIELD_CITY_ID = 'city_id',
        FIELD_CITY_MATCH = 'city_match', 
        
        FIELD_SPECIAL = 'special',      

        FIELD_SET_IS_COUCH = 'set_is_couch', 
        FIELD_COUCH_HOURS = 'couch_hours', 

        FIELD_EDUCATION = 'education', 
        FIELD_TYPE_EDUCATION = 'type_education', 

        FIELD_EXPERIENCES= 'experiences', 
        FIELD_SKILLS= 'skills', 
        FIELD_WORK_COMPANY = 'work_company', 
        FIELD_EXPERIENCE_WORK_YEAR_ALL= 'experience_work_year_all',
        FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL= 'experience_work_base_year_all',
        FIELD_EXPERIENCE_COUNT_WORK_PEOPLE= 'count_work_people',
        FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL = 'count_work_people_all',

        FIELD_LANGUAGES = 'languages', 

        FIELD_LICENCES = 'licences', 
        FIELD_TYPE_CAR = 'type_car', 
        FIELD_TYPE_CONTRACT= 'type_contract', 

        FIELD_EMPLOYMENT  = 'employment', 
        FIELD_WORK_TIME  = 'work_time', 

        FIELD_INCOME_OK = 'income_ok', 
        FIELD_INCOME_WANT = 'income_want', 
        
        FIELD_PERSONAL  = 'personal', 

        FIELD_RESUME  = 'resume', 
        FIELD_PHOTO  = 'photo', 

        FIELD_RATE  = 'rate', 
        FIELD_REASON  = 'reason', 

        FIELD_CONTENT  = 'content'; 


    protected $table = 'questionaires';


    protected $fillable = [        
      
        self::FIELD_WORK_ID,
        self::FIELD_REF,
     
        self::FIELD_OPENED,

        self::FIELD_STATUS,
        self::FIELD_STATUS_NOW,         

        self::FIELD_PHONE,
        self::FIELD_EMAIL,
   
        self::FIELD_FIO_F,   
        self::FIELD_FIO_I,
        self::FIELD_FIO_O,

        self::FIELD_COUNTRY,
       
        self::FIELD_BIRTHDAY,
        self::FIELD_GENDER,

        self::FIELD_CITY_ID,     
        self::FIELD_CITY_MATCH, 
        
        self::FIELD_SPECIAL,        

        self::FIELD_SET_IS_COUCH, 
        self::FIELD_COUCH_HOURS, 

        self::FIELD_EDUCATION,
        self::FIELD_TYPE_EDUCATION,

        self::FIELD_EXPERIENCES,
        self::FIELD_SKILLS,

        self::FIELD_WORK_COMPANY,

        self::FIELD_EXPERIENCE_WORK_YEAR_ALL,
        self::FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL,
        self::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE,
        self::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL,

        self::FIELD_LANGUAGES,

        self::FIELD_LICENCES,
        self::FIELD_TYPE_CAR,
        self::FIELD_TYPE_CONTRACT,

        self::FIELD_EMPLOYMENT,  
        self::FIELD_WORK_TIME, 

        //self::FIELD_INCOME, 
        self::FIELD_INCOME_OK ,
        self::FIELD_INCOME_WANT, 
        
        self::FIELD_PERSONAL, 

        self::FIELD_RESUME,
        self::FIELD_PHOTO,  

        self::FIELD_RATE,  
        self::FIELD_REASON,

        self::FIELD_CONTENT,   
  
    ];    

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id')->first();
    }

}
