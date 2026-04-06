<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAdha extends Model
{
    use HasFactory;

    // Define the table name if it is not the plural form of the model
    protected $table = 'email_adha';

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = [
        'email',
        'is_sent'
    ];

}
