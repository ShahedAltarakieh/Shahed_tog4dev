<?php

namespace App\Models;

use App\Traits\FilterByCategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, FilterByCategoryType;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimonials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'location',
        'location_en',
        'category_id',
        'status',
        'show_in_home'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
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

    public function getImageAttribute()
    {
        $photo = $this->getMedia('testimonials')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('testimonials_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('testimonials_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('testimonials_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getLocalizationName()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['name'] : $this->attributes['name_en'];
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

    public function getLocalizationLocation()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['location'] : $this->attributes['location_en'];
    }
}
