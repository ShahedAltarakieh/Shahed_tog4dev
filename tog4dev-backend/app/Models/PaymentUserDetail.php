<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentUserDetail extends Model
{
    use HasFactory;

    protected $table = 'payment_user_details';

    protected $fillable = [
        'payment_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
    ];

    // Optionally, define relationships if needed
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
