<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    use HasFactory;

    protected $table = 'contact_info';

    protected $guarded = [];

    protected $casts = [
        'extra_phones' => 'array',
        'extra_emails' => 'array',
        'social_links' => 'array',
        'map_lat'      => 'float',
        'map_lng'      => 'float',
    ];

    /**
     * Get the singleton row, creating it if missing.
     */
    public static function current(): self
    {
        $row = static::query()->first();
        if (!$row) {
            $row = static::create(['id' => 1]);
        }
        return $row;
    }

    /**
     * Pick the localized variant of an attribute pair (`field` / `field_ar`).
     */
    public function localized(string $key): ?string
    {
        $locale = app()->getLocale();
        $arKey  = $key . '_ar';
        if ($locale === 'ar') {
            return $this->{$arKey} ?? $this->{$key};
        }
        return $this->{$key} ?? $this->{$arKey};
    }
}
