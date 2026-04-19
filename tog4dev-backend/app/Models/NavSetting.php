<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'label_en',
        'label_ar',
        'visible',
        'order',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'order'   => 'integer',
    ];
}
