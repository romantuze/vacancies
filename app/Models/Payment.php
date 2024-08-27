<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $fillable = ['withdrawal', 'user_id', 'status'];
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->first();
    }

    public function companyName()
    {
        return $this->user()->companyName;
    }

    public function balance()
    {
        $resultSum = 0;
        $user = $this->user();
        $payments = Payment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->get();

        foreach ($payments as $payment) {
            $resultSum += $payment->withdrawal;
        }
        $baseSum = $this->user()->userRevenue() - $resultSum;
        return $baseSum;
    }
}
