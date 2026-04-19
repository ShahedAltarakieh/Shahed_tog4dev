<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'text',
        'text_ar',
        'short_text',
        'short_text_ar',
        'link',
        'cta_text',
        'cta_text_ar',
        'source_type',
        'news_id',
        'badge_type',
        'target_view',
        'is_active',
        'order_no',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInDate($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
        });
    }

    public function scopeForTarget($query, $target)
    {
        if ($target && in_array($target, ['desktop', 'mobile'])) {
            return $query->whereIn('target_view', [$target, 'both']);
        }
        return $query;
    }

    /**
     * Pick a value for the requested locale with bidirectional fallback.
     */
    protected function pickLocalized(?string $en, ?string $ar, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        if ($locale === 'ar') {
            return ($ar !== null && $ar !== '') ? $ar : $en;
        }
        return ($en !== null && $en !== '') ? $en : $ar;
    }

    public function getLocalizedTitle(?string $locale = null): ?string
    {
        return $this->pickLocalized($this->title, $this->title_ar, $locale);
    }

    public function getLocalizedText(?string $locale = null): ?string
    {
        return $this->pickLocalized($this->text, $this->text_ar, $locale);
    }

    public function getLocalizedShortText(?string $locale = null): ?string
    {
        return $this->pickLocalized($this->short_text, $this->short_text_ar, $locale);
    }

    public function getLocalizedCtaText(?string $locale = null): ?string
    {
        return $this->pickLocalized($this->cta_text, $this->cta_text_ar, $locale);
    }
}
