<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDedication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cart_dedication';

    protected $fillable = ['cart_id', 'name', 'dedication_phrase_id', 'dedication_phrase_ar', 'dedication_phrase_en'];
}
