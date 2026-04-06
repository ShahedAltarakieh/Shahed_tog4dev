<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'role',
        'password',
        'admin_password',
        'phone',
        'created_from',
        'organization_name',
        'city',
        'birthday',
        'country',
        'odoo_id',
        'source',
        'need_sync',
        'send_email'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'media'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthday' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['image'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    // Accessor for image path
    public function getImageAttribute()
    {
        $photo = $this->getMedia('users')->last();
        if ($photo) {
            return $photo->getUrl();
        }
        return "/app/assets/images/shared/default-image.jpg";
    }

    public function toOdoo(bool $isCreate = false): array
    {
        $data = [
            'params' => [
                'id' => $this->id,
                'name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'country' => $this->country,
                'city' => $this->city,
                'organization_name' => $this->organization_name,
                'birthday' => $this->birthday
            ],
        ];
        
        return $data;
    }
}