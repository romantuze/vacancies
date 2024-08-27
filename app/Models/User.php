<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'logo',
        'balance',
        'currency',
        'doc_num',
        'doc_date',
        'doc_amount',
        'is_company',
        'is_admin',
        'counter_1',
        'counter_2',
        'counter_3',
        'counter_4',
        'counter_5',
        'api_token',
        'lang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    static function get_logo($logo)
    {
        if (!empty($logo)) {
                $logo_url = "uploads/logo/".$logo;
                $logo = Storage::url($logo_url);
        } else {
            $logo = "";
        }  
        return $logo;  
    } 
}
