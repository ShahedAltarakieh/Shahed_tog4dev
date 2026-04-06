<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForbiddenKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword'
    ];

    /**
     * Scope to get all keywords
     */
    public function scopeActive($query)
    {
        return $query;
    }
}
