<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageMaintenance extends Model
{
    use HasFactory;

    protected $table = 'page_maintenance';

    protected $fillable = [
        'page_key',
        'label_en',
        'label_ar',
        'is_under_update',
        'message_en',
        'message_ar',
        'starts_at',
        'ends_at',
        'order',
    ];

    protected $casts = [
        'is_under_update' => 'boolean',
        'starts_at'       => 'datetime',
        'ends_at'         => 'datetime',
        'order'           => 'integer',
    ];

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_under_update) {
            return false;
        }
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }
        return true;
    }
}
