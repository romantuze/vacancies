<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'skill_id',
        'text',        
    ];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function childrenSkills()
    {
        return $this->hasMany(Skill::class)->with('skills');
    }
}
