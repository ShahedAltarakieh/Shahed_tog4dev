<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class News extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Sluggable;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'slug_en',
        'excerpt',
        'excerpt_en',
        'body',
        'body_en',
        'news_category_id',
        'is_featured',
        'status',
        'published_at',
        'position',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_tablet', 'image_mobile'];

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

    public function scopePublished($query)
    {
        return $query->where('status', 1)->where('published_at', '<=', now());
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function getImageAttribute()
    {
        $photo = $this->getMedia('news')->first();
        return $photo ? $photo->getUrl() : null;
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('news_tablet')->first();
        return $photo ? $photo->getUrl() : $this->getImageAttribute();
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('news_mobile')->first();
        return $photo ? $photo->getUrl() : $this->getImageTabletAttribute();
    }

    public function getLocalizationTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['title'] : ($this->attributes['title_en'] ?? $this->attributes['title']);
    }

    public function getLocalizationExcerpt()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['excerpt'] : ($this->attributes['excerpt_en'] ?? $this->attributes['excerpt']);
    }

    public function getLocalizationBody()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['body'] : ($this->attributes['body_en'] ?? $this->attributes['body']);
    }

    public function getLocalizationSlug()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->slug : ($this->slug_en ?? $this->slug);
    }
}
