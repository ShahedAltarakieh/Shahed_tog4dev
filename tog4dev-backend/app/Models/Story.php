<?php

namespace App\Models;

use App\Traits\FilterByCategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Story extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, FilterByCategoryType;

    protected $fillable = [
        'title',
        'title_en',
        'category_id',
        'status',
        'show_in_home'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en', 'image_tablet', 'image_mobile'];

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Define the relationship with the Category model.
     * (Assuming you have a Category model)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor for image path
    public function getImageAttribute()
    {
        $photo = $this->getMedia('stories')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('stories_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('stories_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('stories_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getLocalizationImage()
    {
        return $this->image;
    }

    public function getLocalizationTitle()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['title'] : $this->attributes['title_en'];
    }
}
