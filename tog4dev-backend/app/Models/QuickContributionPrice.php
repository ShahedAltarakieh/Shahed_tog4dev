<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickContributionPrice extends Model
{
    use HasFactory;

    // Specify the table name if it differs from the model name
    protected $table = 'quick_contribution_prices';

    // Allow mass assignment for the specified columns
    protected $fillable = [
        'quick_contribution_id',
        'price',
        'price_usd',
    ];

    /**
     * Define the relationship with the QuickContribution model.
     */
    public function quickContribution()
    {
        return $this->belongsTo(QuickContribution::class, 'quick_contribution_id');
    }
}
