<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralVisit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'referrer_id',
        'ip',
        'temp_id',
        'user_agent',
        'is_paid',
        'user_id'
    ];

    /**
     * Define the relationship with the user who owns the referral.
     */
    public function referrer()
    {
        return $this->belongsTo(Influencer::class, 'referrer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
