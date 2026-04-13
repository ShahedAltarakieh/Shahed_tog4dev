<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class GalleryVideo extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Sluggable;

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'slug',
        'slug_en',
        'video_url',
        'thumbnail_url',
        'display_target',
        'news_category_id',
        'status',
        'position',
    ];

    protected $hidden = ['media'];

    protected $appends = ['thumbnail'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'method' => function ($string) {
                    return Str::of($string)->replace(' ', '-');
                },
                'onUpdate' => false,
            ],
            'slug_en' => [
                'source' => 'title_en',
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

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function getThumbnailAttribute()
    {
        $photo = $this->getMedia('video_thumbnails')->first();
        return $photo ? $photo->getUrl() : $this->attributes['thumbnail_url'];
    }

    public function getLocalizationTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['title'] : ($this->attributes['title_en'] ?? $this->attributes['title']);
    }

    public function getLocalizationDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['description'] : ($this->attributes['description_en'] ?? $this->attributes['description']);
    }

    public function getLocalizationSlug()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->slug : ($this->slug_en ?? $this->slug);
    }
}
