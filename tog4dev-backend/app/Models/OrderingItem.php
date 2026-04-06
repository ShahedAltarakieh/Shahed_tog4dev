<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderingItem extends Model
{
    use HasFactory;
    
    protected $table = 'ordering_item';
    protected $fillable = ['item_id', 'sort_order', 'type'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}

