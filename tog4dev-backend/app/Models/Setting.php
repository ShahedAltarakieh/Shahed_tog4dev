<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Specify the table associated with the model
    protected $table = 'settings';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'key',
        'value',
        'odoo_id',
        'created_at',
        'updated_at'
    ];
}
