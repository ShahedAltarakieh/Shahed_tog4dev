<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'country_code',
        'language',
        'status',
        'version',
        'meta_title',
        'meta_title_en',
        'meta_description',
        'meta_description_en',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function sections()
    {
        return $this->hasMany(AboutSection::class)->orderBy('sort_order');
    }

    public function versions()
    {
        return $this->hasMany(AboutPageVersion::class)->orderByDesc('version');
    }

    public function scopeForCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    public function scopeGlobal($query)
    {
        return $query->where('country_code', 'global');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
