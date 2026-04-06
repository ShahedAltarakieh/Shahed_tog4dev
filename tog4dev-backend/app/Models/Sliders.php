<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Sliders extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'status',
    ];


    protected $dates = ['deleted_at'];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en', 'image_tablet', 'image_mobile', 'logo', 'logo_en'];

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImageAttribute()
    {
        $photo = $this->getMedia('sliders')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('sliders_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('sliders_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('sliders_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getLogoAttribute()
    {
        $photo = $this->getMedia('sliders_logo')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getLogoENAttribute()
    {
        $photo = $this->getMedia('sliders_logo_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
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
        return $this->image;
    }

    public function getLocalizationLogo()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->logo : $this->logo_en;
    }

}
