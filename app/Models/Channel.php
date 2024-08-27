<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'channel_name',
        'aRecords',
        'geoCoverage',
        'details',
        'archive',
        'user_id',
        'commission',
        'commission_type',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->first();
    }

    public function statistic()
    {
        return $this->hasMany(Statistic::class, 'channel_id', 'id')->get();
    }

    public function userInfo()
    {
        $user = $this->user();
        if ($user)
            return ['companyName' => $user->companyName, 'id' => $user->id];
        else return [];
    }

    public function scopeByUserId($builder, $user_id)
    {
        return $builder->where('id', $user_id);
    }

    public function infoTotal()
    {
        $stat = $this->statistic();
        $rev = 0;
        foreach ($stat as $item) {
            $rev += $item->revenue;
        }

        return [
            'channel' => $this->channel_name,
            'revenue' => round($rev, 2)
        ];
    }

}
