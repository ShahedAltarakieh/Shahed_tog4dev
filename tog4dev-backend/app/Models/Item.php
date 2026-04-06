<?php

namespace App\Models;

use App\Traits\FilterByCategoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class Item extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, FilterByCategoryType, Sluggable;
    protected $fillable = [
        'title',
        'title_en',
        'has_beneficiary',
        'description',
        'description_en',
        'beneficiaries_message',
        'beneficiaries_message_en',
        'description_after_payment',
        'description_after_payment_en',
        'location',
        'location_en',
        'category_id',
        'payment_type',
        'status',
        'amount',
        'amount_usd',
        'account_label',
        'account_label_en',
        'analyticـaccount',
        'slug',
        'slug_en',
        'show_in_home',
        'odoo_id',
        'source',
        'single_price',
        'single_price_usd',
        'need_sync',
        'position'
    ];

    /**
     * Condolence / dedication phrases (ar/en) for use in cart/dedications.
     */
    public const UMRA_DEDICATION_PHRASES = [
        ['id' => 1, 'ar' => 'رحمه الله', 'en' => 'May God have mercy on him'],
        ['id' => 2, 'ar' => 'رحمها الله', 'en' => 'May God have mercy on her'],
        ['id' => 3, 'ar' => 'عافاه الله', 'en' => 'May God heal him'],
        ['id' => 4, 'ar' => 'عافاها الله', 'en' => 'May God heal her'],
    ];

    /**
     * Return dedication phrases for the current locale (id + text in ar or en).
     *
     * @param string|null $locale e.g. 'ar', 'en'. Defaults to app()->getLocale().
     * @return array<int, array{id: int, text: string}>
     */
    public static function getDedicationPhrasesByLocale(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $key = in_array($locale, ['ar', 'en'], true) ? $locale : 'en';

        return array_map(
            fn (array $phrase) => ['id' => $phrase['id'], 'text' => $phrase[$key]],
            self::UMRA_DEDICATION_PHRASES
        );
    }

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function additionalInfo()
    {
        return $this->hasOne(AdditionalInfo::class); // Assuming one-to-one relationship
    }

    public function cartItemsPaid()
    {
        return $this->hasMany(Cart::class)->where('model_type','App\Models\Item')->where('is_paid', 1);
    }

    public function priceOptions()
    {
        return $this->hasMany(PriceOption::class);
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function ordering()
    {
        return $this->hasOne(OrderingItem::class);
    }

    protected $dates = ['deleted_at'];

    protected $hidden = ['media'];

    protected $appends = ['image', 'image_en', 'image_tablet', 'image_mobile', 'image_item'];

    public function getImageAttribute()
    {
        $photo = $this->getMedia('items')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return '';
    }

    public function getImageTabletAttribute()
    {
        $photo = $this->getMedia('items_tablet')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return $this->getImageAttribute();
    }

    public function getImageMobileAttribute()
    {
        $photo = $this->getMedia('items_mobile')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return $this->getImageTabletAttribute();
    }

    public function getImageItemAttribute()
    {
        return null;
    }

    public function getImageENAttribute()
    {
        $photo = $this->getMedia('items_en')->first();
        if ($photo) {
            return $photo->getUrl();
        }
        return asset('/assets/admin/images/profile/17.jpg');
    }

    public function getItemGalleryAttribute()
    {
        $photo = $this->getMedia('items_gallery');
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

    public function getLocalizationLocation()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['location'] : $this->attributes['location_en'];
    }

    public function getLocalizationImage()
    {
        return $this->image;
    }

    public function getLocalizationSlug()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->slug : $this->slug_en;
    }

    public function toOdoo(bool $isCreate = false): array
    {

        $additionalInfo = $this->additionalInfo;

        $dropdownList = [];
        foreach ($this->priceOptions as $option) {
            $dropdownList[] = [
                'website_dropdown_id' => $option->id,
                'title1' => $option->d1_option_en,
                'title1_ar' => $option->d1_option,
                'title2' => $option->d2_option_en,
                'title2_ar' => $option->d2_option,
                'price' => $option->price,
                'price_usd' => $option->price_en,
                'is_default' => (bool)$option->is_default,
            ];
        }

        $price_list = [];
        foreach($this->itemPrices as $price){
            $price_list[] = [
                "website_price_id" => $price->id,
                "price" => $price->price,
                "price_usd" => $price->price_en
            ];
        }

        if($this->payment_type == "Subscription"){
            $payment_type = "subscription";
        } else if($this->payment_type == "One-Time"){
            $payment_type = "one_time";
        } else {
            $payment_type = "both";
        }

        return [
            'params' => [
                'id' => $this->id,
                'category_id' => $this->category_id,
                'name' => $this->title_en,
                'title_ar' => $this->title,
                'location_en' => $this->location_en,
                'location' => $this->location,
                'slug_en' => $this->slug_en,
                'slug_ar' => $this->slug,
                'active' => true, //$this->status,
                'description_en' => $this->description_en,
                'description_ar' => $this->description,
                'price' => $this->amount,
                'price_usd' => $this->amount_usd,
                'payment_type' => $payment_type,
                'project_type' => (string) $this->category->type ?? '',
                'project_story_en' => $additionalInfo->project_story_en ?? null,
                'project_story_ar' => $additionalInfo->project_story ?? null,
                'bold_description_en' => $additionalInfo->bold_description_en ?? null,
                'bold_description_ar' => $additionalInfo->bold_description ?? null,
                'normal_description_en' => $additionalInfo->normal_description_en ?? null,
                'normal_description_ar' => $additionalInfo->normal_description ?? null,
                'dropdown_list' => $dropdownList,
                'price_list' => $price_list,
                'image' => $this->getImageAttribute(),
                'image_en' => $this->getImageENAttribute()
            ]
        ];
    }
    
    public function getLocalizationAccountLabel()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->account_label : $this->account_label_en;
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable', 'model_type', 'model_id');
    }

    public function getType()
    {
        return (new \App\Helpers\Helper)->getFlipTypes($this->category->type) ?? null;
    }

}
