<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'collection_team';

    protected $fillable = [
        'user_id', 'email', 'first_name', 'last_name', 'phone', 'country', 'city', 'address',
    ];

    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'collection_team_id');
    }

}
