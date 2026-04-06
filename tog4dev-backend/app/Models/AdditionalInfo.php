<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the SoftDeletes trait

class AdditionalInfo extends Model
{
    use HasFactory, SoftDeletes; // Use the SoftDeletes trait

    protected $table = 'additional_info';

    protected $fillable = [
        'item_id',
        'project_story',
        'project_story_en',
        'bold_description',
        'bold_description_en',
        'normal_description',
        'normal_description_en',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getLocalizationProjectStory()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['project_story'] : $this->attributes['project_story_en'];
    }

    public function getLocalizationBoldDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['bold_description'] : $this->attributes['bold_description_en'];
    }

    public function getLocalizationNormalDescription()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->attributes['normal_description'] : $this->attributes['normal_description_en'];
    }
}
