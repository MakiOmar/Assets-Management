<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liability extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'amount', 'due_date', 'type'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Methods
    public static function getTotalDebtByUser($userId)
    {
        return self::where('user_id', $userId)->sum('amount');
    }

    public function reduceAmount($payment)
    {
        $this->amount -= $payment;
        $this->save();
    }

    public function increaseAmount($amount)
    {
        $this->amount += $amount;
        $this->save();
    }
}
