<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingZbooniItem extends Model
{
    use HasFactory;

    // Define the table name if it is not the plural form of the model
    protected $table = 'mapping_zbooni_item';

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = [
        'item_id',
        'zbooni_name',
        'model_type'
    ];

    /**
     * Define the relationship with the Item model (One-to-One)
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');  // 'item_id' is the foreign key
    }

    public function quickContribution()
    {
        return $this->belongsTo(QuickContribution::class, 'item_id');  // 'item_id' is the foreign key
    }
}
