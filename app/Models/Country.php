<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/16/2021
 * Time: 11:50 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//таблица страны
class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'text',        
    ];

    protected $table = 'countries';

    public $timestamps = false;

    public function get_countries_question($country_str)
    {
            $country_array =  json_decode($country_str);
            $country_array_new = [];
            if (!empty($country_array)) { 
              foreach ($country_array as $country_array_value) {
                if (!empty($country_array_value)) {
                  array_push($country_array_new, $country_array_value);
                }
              }
              $country_string =  implode(", ", $country_array_new);
            }
            else { $country_string = "-"; }
            return $country_string;
    }

}