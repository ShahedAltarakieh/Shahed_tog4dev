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

        static::saved(function () {
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

    // Atomic single-default enforcement.
    public function save(array $options = []): bool
    {
        return DB::transaction(function () use ($options) {
            if ($this->exists
                && $this->getOriginal('is_default')
                && !$this->is_default) {
                $remainingDefaults = static::where('is_default', true)
                    ->where('id', '!=', $this->id)
                    ->count();
                if ($remainingDefaults === 0) {
                    throw new \RuntimeException(
                        'Cannot unset the only default language. Promote another language to default first.'
                    );
                }
            }

            if ($this->is_default) {
                $query = static::where('is_default', true);
                if ($this->exists) {
                    $query->where('id', '!=', $this->id);
                }
                $query->update(['is_default' => false]);
            }

            return parent::save($options);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function defaultCode(): string
    {
        $code = static::query()->where('is_default', true)->value('code');
        if ($code) { return $code; }
        // Self-healing: if no default exists (edge case from a failed admin
        // operation), promote the first active row (or any row) so the system
        // always converges to having exactly one default.
        $row = static::query()->orderByDesc('is_active')->orderBy('position')->orderBy('id')->first();
        if ($row) {
            $row->is_default = true;
            $row->save();
            return $row->code;
        }
        return 'en';
    }

    public static function activeCodes(): array
    {
        return static::active()->pluck('code')->all();
    }
}
