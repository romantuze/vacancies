<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

//таблица города
class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'text',
        'region_id',    
    ];

    protected $table = 'cities';

    public $timestamps = false;

    public function get_region_id($city_id)
    {
        $region_id = NULL;
        if(isset($city_id)) {   
                $item_city = City::where('text',$city_id)->first();
                if(isset($item_city)) {   
                    if(isset($item_city->region_id)) {   
                        $region_id = (int) $item_city->region_id; 
                    }
                }
        }
        return $region_id;
    }

    public function get_region_text($city_id)
    {
        $region = "";
        if(isset($city_id)) {  
            $item_city = City::where('text',$city_id)->first();
            if(isset($item_city)) {   
                if(isset($item_city->region_id)) {   
                    $region_id = (int) $item_city->region_id; 
                    $item_region = Region::find($region_id);
                     if(isset($item_region->text)) { 
                        $region = $item_region->text;
                     }
                }
            }
        }
        return $region;
    }


    public function get_country_id($region_id)
    {
        $country_id = NULL;

        if(isset($region_id)) {            
                $region_id = (int) $region_id;
                $item_region = Region::find($region_id);
                if(isset($item_region)) {   
                    if(isset($item_region->country_id)) {   
                        $country_id = (int) $item_region->country_id; 
                    }
                }
        }
        return $country_id;
    }



}