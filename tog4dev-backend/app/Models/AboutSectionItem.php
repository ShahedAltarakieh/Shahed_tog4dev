<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_section_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'image',
        'icon',
        'link',
        'link_en',
        'value',
        'label',
        'label_en',
        'social_links',
        'extra',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'social_links' => 'array',
        'extra' => 'array',
        'is_visible' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(AboutSection::class, 'about_section_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
