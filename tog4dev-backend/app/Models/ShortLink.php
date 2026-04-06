<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ShortLink extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes here

    protected $fillable = ['original_url', 'short_code'];

    protected $dates = ['deleted_at']; // optional, Laravel handles it automatically

    // Generate unique short code before creating
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->short_code) {
                $model->short_code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode($length = 8)
    {
        do {
            $code = Str::random($length); // random 8 chars
        } while (self::where('short_code', $code)->exists());

        return $code;
    }
}
