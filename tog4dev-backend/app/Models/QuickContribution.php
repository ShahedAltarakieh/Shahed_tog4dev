<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class QuickContribution extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    // Specify the table name if it differs from the model name
    protected $table = 'quick_contribution';

    // Allow mass assignment for the specified columns
    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'beneficiaries_message',
        'beneficiaries_message_en',
        'description_after_payment',
        'description_after_payment_en',
        'location',
        'location_en',
        'target',
        'target_usd',
        'account_label',
        'account_label_en',
        'analyticـaccount',
        'type_id',
        'category_id',
        'status',
        'odoo_id',
        'single_price',
        'single_price_usd',
        'source',
        'need_sync',
    ];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en', 'image_tablet', 'image_mobile'];

    public function scopeGetActive($query)
    {
        return $query->where('status', 1);
    }
    
    /**
     * Optionally, define relationships if needed.
     */

    // Example: Relationship with Category model (if applicable)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(QuickContributionPrice::class);
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

    public function getImageAttribute()
    {
        $photo = $this->getMedia('quick_contribute')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('quick_contribute_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('quick_contribute_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return null;
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('quick_contribute_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
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

    public function toOdoo(bool $isCreate = false): array
    {
        if (! $this->relationLoaded('prices')) {
            $this->load('prices');
        }

        $price_list = [];
        foreach($this->prices as $price){
            $price_list[] = [
                "website_price_id" => $price->id,
                "price" => $price->price,
                "price_usd" => $price->price_usd
            ];
        }

        $data = [
            'params' => [
                'name' => $this->title_en,
                'name_ar' => $this->title,
                'id' => $this->id,
                'category_id' => $this->category_id,
                'location' => $this->location_en,
                'location_ar' => $this->location,
                'description_en' => $this->description_en,
                'description_ar' => $this->description,
                'target' => $this->target?? 0,
                'target_usd' => $this->target_usd ?? 0,
                'active' => true, //(bool) $this->status,
                'project_type' => (string) $this->type_id,
                'price_list' => $price_list,
                'image' => $this->getImageAttribute(),
                'image_en' => $this->getImageENAttribute()
            ],
        ];

        return $data;
    }

    public function getLocalizationAccountLabel()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->account_label : $this->account_label_en;
    }

    public function cartItemsPaid()
    {
        return $this->hasMany(Cart::class, 'item_id')->where('model_type','App\Models\QuickContribution')->where('is_paid', 1);
    }
}
