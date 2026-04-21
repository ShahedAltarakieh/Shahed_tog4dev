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

    /**
     * Persist the row inside a single DB transaction so that toggling the
     * default flag is atomic: the previously-default row is unset and the
     * new one is set within the same transaction. If the final write fails
     * the rollback restores the prior default — the system never ends up
     * with zero defaults. We also reject self-demotion of the only default
     * row (callers must promote another row in the same request instead).
     */
    public function save(array $options = []): bool
    {
        return DB::transaction(function () use ($options) {
            // Prevent unsetting the only remaining default. Promoting a
            // different row to default uses set_default which always passes
            // is_default=true and goes through the branch below.
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
        return $code ?: 'en';
    }

    public static function activeCodes(): array
    {
        return static::active()->pluck('code')->all();
    }
}
