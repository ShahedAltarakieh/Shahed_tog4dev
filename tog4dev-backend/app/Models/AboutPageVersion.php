<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_page_id',
        'version',
        'snapshot',
        'action',
        'created_by',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(AboutPage::class, 'about_page_id');
    }
}
