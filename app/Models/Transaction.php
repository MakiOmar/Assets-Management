<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'asset_id', 'liability_id', 'amount', 'type', 'date'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function liability()
    {
        return $this->belongsTo(Liability::class);
    }

    // Methods
    public static function recordTransaction(array $data)
    {
        return self::create($data);
    }

    public static function getRecentTransactionsByUser($userId, $limit = 10)
    {
        return self::where('user_id', $userId)->orderBy('date', 'desc')->take($limit)->get();
    }

    public static function getTransactionsByUser($userId, $perPage = 10)
    {
        return self::where('user_id', $userId)
        ->orderBy('date', 'desc')
        ->paginate($perPage);
    }
}
