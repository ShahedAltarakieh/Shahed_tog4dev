<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    use HasFactory;
    protected $table = 'influencers';

    protected $fillable = [
        'name',
        'code',
        'page_link',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function isExpired()
    {
        if ($this->expiry_date) {
            return now()->greaterThan($this->expiry_date);
        }
        return false;
    }

    public function visits()
    {
        return $this->hasMany(ReferralVisit::class, 'referrer_id', 'id')->where('is_paid', 0);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'referrer_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasManyThrough(Subscription::class, Payment::class, 'referrer_id', 'payment_id', 'id', 'id');
    }

}
