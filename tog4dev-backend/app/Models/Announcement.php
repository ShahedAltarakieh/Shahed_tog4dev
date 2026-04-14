<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'short_text',
        'link',
        'cta_text',
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
}
