<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'direction',
        'is_default',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
        'position'   => 'integer',
    ];

    public const CACHE_KEY = 'languages.public.payload';

    protected static function booted(): void
    {
        static::saving(function (Language $lang) {
            $lang->code = strtolower(trim($lang->code));
            if (!in_array($lang->direction, ['ltr', 'rtl'], true)) {
                $lang->direction = 'ltr';
            }
        });

        static::saved(function (Language $lang) {
            // Enforce single default
            if ($lang->is_default) {
                static::where('id', '!=', $lang->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
            static::bustCache();
        });

        static::deleted(function () {
            static::bustCache();
        });
    }

    public static function bustCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function defaultCode(): string
    {
        $code = static::query()->where('is_default', true)->value('code');
        return $code ?: 'en';
    }

    public static function activeCodes(): array
    {
        return static::active()->pluck('code')->all();
    }
}
