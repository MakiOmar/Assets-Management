<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public static function getRecentByUser($userId, $limit = 10)
    {
        return self::where('user_id', $userId)->orderBy('date', 'desc')->take($limit)->get();
    }

    public static function getByUser($userId, $perPage = 10)
    {
        return self::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    }

    public static function getAssets($request)
    {
        // Check if the user is an admin
        if ($request->user()->hasRole('administrator')) {
            // Admin: Get all transactions with pagination (10 per page)

            $transactions = self::orderBy('created_at', 'desc')->paginate(10);
        } else {
            // Non-admin: Get transactions only for the logged-in user with pagination (10 per page)
            $transactions = self::getByUser($request->user()->id, 10);
        }
        return $transactions;
    }
}
