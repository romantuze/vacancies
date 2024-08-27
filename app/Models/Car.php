<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//тип автомобиля
class Car extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'text',
        'text_en'
    ];

    public static function car_encode($car_request) {
    	$type_car = [];
        $type_car_request = $car_request;
        foreach($type_car_request as $typecar_key => $typecar_value) {
            if (!empty($type_car_request[$typecar_key]['text']) && !empty($type_car_request[$typecar_key]['degree'])) {
                 array_push($type_car, $typecar_value);
            }               
        }      
        $type_car = json_encode($type_car, JSON_UNESCAPED_UNICODE);
        return $type_car;
    }

}
