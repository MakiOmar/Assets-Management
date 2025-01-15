<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    // Methods
    public static function getCategoriesByType($type)
    {
        return self::where('type', $type)->get();
    }
}
