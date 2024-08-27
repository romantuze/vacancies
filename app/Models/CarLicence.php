<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarLicence extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'text',
        'text_en'
    ];

    public static function license_encode($license_request) {
    	$licences = [];
        $licences_request = $license_request;
        foreach($licences_request as $licences_key => $licences_value) {
            if (!empty($licences_request[$licences_key]['text']) && !empty($licences_request[$licences_key]['degree'])) {
                 array_push($licences, $licences_value);
            }               
        }      
        $licences = json_encode($licences, JSON_UNESCAPED_UNICODE);
        return $licences;
    }   
}
