<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/16/2021
 * Time: 11:52 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'text',
        'country_id',
    ];

    protected $table = 'regions';

    public $timestamps = false;
}