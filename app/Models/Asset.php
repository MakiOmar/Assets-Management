<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'value', 'type'];

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
    public static function getTotalValueByUser($userId)
    {
        return self::where('user_id', $userId)->sum('value');
    }

    public function increaseValue($amount)
    {
        $this->value += $amount;
        $this->save();
    }

    public function decreaseValue($amount)
    {
        if ($this->value >= $amount) {
            $this->value -= $amount;
            $this->save();
        }
    }
}
