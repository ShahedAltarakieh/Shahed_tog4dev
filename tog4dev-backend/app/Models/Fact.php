<?php

namespace App\Models;

use App\Traits\FilterByCategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Fact extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, FilterByCategoryType, InteractsWithMedia;

    protected $table = 'facts';

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'category_id',
        'status'
    ];

    protected $hidden = ['media'];

    protected $appends = ['logo', 'logo_en'];

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }

    // Relationships (If applicable, you can define relationships like 'category' here)
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function getLogoAttribute()
    {
        $photo = $this->getMedia('facts')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getLogoENAttribute()
    {
        $photo = $this->getMedia('facts_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getLocalizationTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['title'] : $this->attributes['title_en'];
    }

    public function getLocalizationDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['description'] : $this->attributes['description_en'];
    }

    public function getLocalizationImage()
    {
        return $this->logo;
    }
}
