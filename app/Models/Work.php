<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questionaire;

class Work extends Model
{
    use HasFactory;


    CONST FIELD_ID = 'id',             
        FIELD_USER_ID = 'user_id',
        FIELD_SLUG = 'slug',   
        FIELD_STATUS = 'status',     
        FIELD_COST = 'cost',     
        FIELD_BALANCE = 'balance',
        FIELD_BALANCE_ADMIN = 'balance_admin', 
        FIELD_BALANCE_FREE = 'balance_free', 
        FIELD_DEPOSIT_TEXT = 'deposit_text',
        FIELD_DEPOSIT_IN = 'deposit_in', 
        FIELD_DEPOSIT_CONFIRM = 'deposit_confirm', 
        FIELD_SALE = 'sale', 
        FIELD_PAY_CONFIRM = 'pay_confirm', 
        FIELD_DATE_CREATE = 'date_create',     
        FIELD_DATE_CLOSE = 'date_close',             
        FIELD_PUBLISH_COMPANY = 'publish_company', 
        FIELD_NAME = 'name',   
        FIELD_POSITIONS = 'positions', 
        FIELD_CITY_ID= 'city_id', 
        FIELD_SPECIAL= 'special', 
        FIELD_IS_VIEW_SPECIAL = 'is_view_special', 
        FIELD_GENDER = 'gender', 
        FIELD_AGE_START = 'age_start', 
        FIELD_AGE_END = 'age_end',
        FIELD_EDUCATION = 'education', 
        FIELD_TYPE_EDUCATION = 'type_education',
        FIELD_SET_IS_COUTCH= 'set_is_coutch', 
        FIELD_EXPERIENCES= 'experiences', 
        FIELD_SKILLS= 'skills',        
        FIELD_EXPERIENCE_WORK_YEAR_ALL= 'experience_work_year_all', 
        FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL= 'experience_work_base_year_all', 
        FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL = 'count_work_people_all',
        FIELD_LANGUAGES = 'languages', 
        FIELD_LICENCES = 'licences', 
        FIELD_TYPE_CAR = 'type_car',
        FIELD_TYPE_CONTRACT= 'type_contract', 
        FIELD_INCOME = 'income', 
        FIELD_INCOME_START = 'income_start', 
        FIELD_INCOME_END = 'income_end', 
        FIELD_CURRENCY  = 'currency',
        FIELD_PUBLISH_INCOME  = 'publish_income', /
        FIELD_EMPLOYMENT  = 'employment', 
        FIELD_WORK_TIME  = 'work_time', 
        FIELD_FACILITATION  = 'facilitation', 
        FIELD_PERSONAL  = 'personal',
        FIELD_CONTENT  = 'content';   

    protected $table = 'works';


    protected $fillable = [       
        self::FIELD_USER_ID,
        self::FIELD_SLUG,
        self::FIELD_STATUS,
        self::FIELD_COST,
        self::FIELD_BALANCE,
        self::FIELD_BALANCE_ADMIN,
        self::FIELD_BALANCE_FREE,
        self::FIELD_DEPOSIT_TEXT,
        self::FIELD_DEPOSIT_IN,
        self::FIELD_DEPOSIT_CONFIRM, 
        self::FIELD_SALE,
        self::FIELD_PAY_CONFIRM,
        self::FIELD_DATE_CREATE,  
        self::FIELD_DATE_CLOSE,
        self::FIELD_PUBLISH_COMPANY,        
        self::FIELD_NAME,    
        self::FIELD_POSITIONS,
        self::FIELD_CITY_ID,     
        self::FIELD_SPECIAL,   
        self::FIELD_IS_VIEW_SPECIAL,
        self::FIELD_GENDER,
        self::FIELD_AGE_START,
        self::FIELD_AGE_END,     
        self::FIELD_EDUCATION,
        self::FIELD_TYPE_EDUCATION,
        self::FIELD_SET_IS_COUTCH,
        self::FIELD_EXPERIENCES,
        self::FIELD_SKILLS,
        self::FIELD_EXPERIENCE_WORK_YEAR_ALL,
        self::FIELD_EXPERIENCE_WORK_BASE_YEAR_ALL,
        self::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE,
        self::FIELD_EXPERIENCE_COUNT_WORK_PEOPLE_ALL,
        self::FIELD_LANGUAGES,
        self::FIELD_LICENCES,
        self::FIELD_TYPE_CAR,
        self::FIELD_TYPE_CONTRACT,
        self::FIELD_INCOME, 
        self::FIELD_INCOME_START,
        self::FIELD_INCOME_END,
        self::FIELD_CURRENCY,
        self::FIELD_PUBLISH_INCOME,
        self::FIELD_EMPLOYMENT,
        self::FIELD_WORK_TIME,
        self::FIELD_FACILITATION,
        self::FIELD_PERSONAL,
        self::FIELD_CONTENT,  
    ];

    protected $casts  = [
       
    ];

    public function company()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->first();
    }

    public function questionaries()
    {
        return $this->hasMany(Questionaire::class);
    }

    public static function get_link($slug) 
    {
        $work_link = "";
        $url = request()->getHost(); 
        if (!empty($slug)) {
           $work_link = $url."/q/".$slug;          
        }
        return $work_link;
    }

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function get_sum_open($income, $sale) {
        $sum_open = intval($income * 0.21 * ( (100 - $sale) / 100 ) );
        return $sum_open;
    }

    public function get_sum_replace($income, $sale) {
        $sum_replace = intval($income * 0.06 * ( (100 - $sale) / 100 ) );
        return $sum_replace;
    }

    public static function get_date_close() {
        $days = 15;
        $current_date = time();
        $current_date += 60*60*24*$days;
        $date_close = date('Y-m-d h:i:s', $current_date);
        return $date_close;
    }

}
