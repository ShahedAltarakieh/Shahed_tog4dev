<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Seo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'model_id',
        'model_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_title_en',
        'meta_description_en',
        'meta_keywords_en',
    ];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en'];

    public function seoable()
    {
        // manual polymorphic relation
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function getLocalizationTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['meta_title'] : $this->attributes['meta_title_en'];
    }

    public function getLocalizationDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['meta_description'] : $this->attributes['meta_description_en'];
    }

    public function getLocalizationKeywords()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['meta_keywords'] : $this->attributes['meta_keywords_en'];
    }

    public function getLocalizationImage()
    {
        return $this->image;
    }

    public function getImageAttribute()
    {
        $photo = $this->getMedia('seo')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('seo_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }
}
