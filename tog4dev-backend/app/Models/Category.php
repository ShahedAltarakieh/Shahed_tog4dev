<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;


class Category extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'hero_title',
        'hero_title_en',
        'hero_description',
        'hero_description_en',
        'type',
        'status',
        'all_option',
        'slug',
        'slug_en',
        'odoo_id',
        'source',
        'need_sync',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string>
     */
    protected $dates = ['deleted_at'];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en', 'hero', 'hero_en', 'hero_image_tablet', 'hero_image_mobile'];

    public function sluggable(): array
    {
        return [
            // Arabic slug
            'slug' => [
                'source' => 'title',
                'method' => function ($string) {
                    return Str::of($string)->replace(' ', '-');
                },
                'onUpdate' => false
            ],
            // English slug
            'slug_en' => [
                'source' => 'title_en',
                'method' => function ($string) {
                    return Str::slug($string, '-');
                },
                'onUpdate' => false
            ]
        ];
    }

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeGetOrganization($query)
    {
        return $query->where('type', 1);
    }

    public function scopeGetProjects($query)
    {
        return $query->where('type', 2);
    }

    public function scopeGetCrowdfunding($query)
    {
        return $query->where('type', 3);
    }

    public function scopeGetHome($query)
    {
        return $query->where('type', 4);
    }

    public function getImageAttribute()
    {
        $photo = $this->getMedia('categories')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('categories_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getHeroImageAttribute()
    {
        $photo = $this->getMedia('categories_hero')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getHeroENAttribute()
    {
        $photo = $this->getMedia('categories_hero_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getHeroImageTabletAttribute()
    {
        $photo = $this->getMedia('categories_hero_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getHeroImageMobileAttribute()
    {
        $photo = $this->getMedia('categories_hero_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
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

    public function getLocalizationHeroDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['hero_description'] : $this->attributes['hero_description_en'];
    }

    public function getLocalizationHeroTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['hero_title'] : $this->attributes['hero_title_en'];
    }

    public function getLocalizationImage()
    {
        return $this->image;
    }

    public function getLocalizationHeroImage()
    {
        return $this->hero_image;
    }

    public function getLocalizationSlug()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->slug : $this->slug_en;
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Item::class);
    }

    public function toOdoo(bool $isCreate = false): array
    {
        $data = [
            'params' => [
                'name' => $this->title_en,
                'title_ar' => $this->title,
                'id' => $this->id,
                'slug' => $this->slug_en,
                'slug_ar' => $this->slug,
                'description_en' => $this->description_en,
                'description_ar' => $this->description,
                'hero_title_en' => $this->hero_title_en,
                'hero_title_ar' => $this->hero_title,
                'hero_description_en' => $this->hero_description_en,
                'hero_description_ar' => $this->hero_description,
                'active' => true, //$this->status,
                'is_all_option' => $this->all_option,
                'project_type' => (string) $this->type,
                "image_ar" => $this->getImageAttribute(),
                "image_en" => $this->getImageENAttribute(),
                "hero_image_ar" => $this->getHeroImageAttribute(),
                "hero_image_en" => $this->getHeroENAttribute()
            ],
        ];

        return $data;
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable', 'model_type', 'model_id');
    }
}
