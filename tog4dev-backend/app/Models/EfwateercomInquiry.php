<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfwateercomInquiry extends Model
{
    use HasFactory;

    protected $table = 'efwateercom_inquiries';

    protected $fillable = [
        'mobile_number',
        'service_type',
        'parent_id',
        'efwateercom_service_id',
        'user_id',
        'customer_name',
    ];

    protected $casts = [
        'parent_id' => 'string',
        'efwateercom_service_id' => 'integer',
    ];
}
