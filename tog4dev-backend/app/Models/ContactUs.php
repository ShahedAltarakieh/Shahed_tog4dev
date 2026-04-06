<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'organization_name',
        'email',
        'phone',
        'message',
        'type',
        'status',
        'is_read',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => 'integer',
        'status' => 'integer',
        'is_read' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    public function scopeGetProjects($query)
    {
        return $query->where('type', 2);
    }

    public function scopeGetOrganization($query)
    {
        return $query->where('type', 1);
    }

}
