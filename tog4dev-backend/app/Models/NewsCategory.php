<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'slug_en',
        'status',
        'position',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'method' => function ($string) {
                    return Str::of($string)->replace(' ', '-');
                },
                'onUpdate' => false,
            ],
            'slug_en' => [
                'source' => 'name_en',
                'method' => function ($string) {
                    return Str::slug($string, '-');
                },
                'onUpdate' => false,
            ],
        ];
    }

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function galleryPhotos()
    {
        return $this->hasMany(GalleryPhoto::class);
    }

    public function galleryVideos()
    {
        return $this->hasMany(GalleryVideo::class);
    }

    public function getLocalizationName()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['name'] : ($this->attributes['name_en'] ?? $this->attributes['name']);
    }

    public function getLocalizationSlug()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->slug : ($this->slug_en ?? $this->slug);
    }
}
