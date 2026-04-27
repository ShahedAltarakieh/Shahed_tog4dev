<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_page_id',
        'section_key',
        'title',
        'title_en',
        'subtitle',
        'subtitle_en',
        'body',
        'body_en',
        'image',
        'video_url',
        'cta_text',
        'cta_text_en',
        'cta_link',
        'cta_link_en',
        'layout',
        'settings',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_visible' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(AboutPage::class, 'about_page_id');
    }

    public function items()
    {
        return $this->hasMany(AboutSectionItem::class)->orderBy('sort_order');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
