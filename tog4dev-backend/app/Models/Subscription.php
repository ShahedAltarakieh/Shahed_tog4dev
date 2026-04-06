<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'item_id',
        'subscription_id',
        'payment_id',
        'price',
        'temp_id',
        'model_type',
        'start_date',
        'end_date',
        'status',
        'send_reminder',
        'title',
        'title_en',
        'description',
        'description_en',
        'location',
        'location_en'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the User model
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    // Optionally, if there's a relationship with items
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function quickContribute()
    {
        return $this->belongsTo(QuickContribution::class, 'item_id');
    }
}
