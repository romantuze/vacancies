<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'personal_id',
        'text',
        'text_en',
    ];

    public function children()
    {
        return $this->hasMany(Personal::class);
    }


    public static function personal_encode($personal_request)
    {
        $personal = [];  
        if (!empty($personal_request)) {
            $request_personal = $personal_request;           
            foreach($request_personal as $personal_key => $personal_value) {
                if (!empty($request_personal[$personal_key]['text'])) {
                    array_push($personal, $request_personal[$personal_key]['text']);
                }               
            }
        }
        $personal = json_encode($personal, JSON_UNESCAPED_UNICODE);
        return $personal;
    }
}
