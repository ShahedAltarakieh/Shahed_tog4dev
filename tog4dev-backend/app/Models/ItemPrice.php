<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'price', 'price_en'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
