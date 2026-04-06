<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'd1_option',
        'd1_option_en',
        'd2_option',
        'd2_option_en',
        'price',
        'price_en',
        'is_default',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getLocalizationD1Option()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['d1_option'] : $this->attributes['d1_option_en'];
    }

    public function getLocalizationD2Option()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['d2_option'] : $this->attributes['d2_option_en'];
    }
}
