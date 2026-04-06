<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id', 'item_id','subscription_id' , 'payment_id' , 'price', 'collection_team_id', 'type', 
        'is_paid', 'temp_id', 'option_id', 'option_label', 'model_type', 'quantity', 'created_at', 'updated_at',
        'title', 'title_en', 'description', 'description_en', 'location', 'location_en', 'analyticـaccount_id', 'has_beneficiary'
    ];


    public function relatedModel()
    {
        if ($this->model_type === Item::class) {
            return $this->belongsTo(Item::class, 'item_id');
        } elseif ($this->model_type === QuickContribution::class) {
            return $this->belongsTo(QuickContribution::class, 'item_id');
        }

        return null; // Return null if the model_type does not match
    }

    public function model()
    {
        return $this->morphTo(null, 'model_type', 'item_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'cart_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function quickContribute()
    {
        return $this->belongsTo(QuickContribution::class, 'item_id');
    }

    public function dedications()
    {
        return $this->hasMany(CartDedication::class, 'cart_id');
    }
}
