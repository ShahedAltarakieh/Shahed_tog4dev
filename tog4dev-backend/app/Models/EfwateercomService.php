<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EfwateercomService extends Model
{
    use HasFactory;

    protected $table = 'efwateercom_services';

    protected $fillable = [
        'parent_id',
        'service_type',
        'model_type',
        'model_id',
        'price',
        'option_id',
    ];

    protected $casts = [
        'parent_id' => 'string',
        'price' => 'decimal:2',
        'option_id' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Polymorphic target (model_type + model_id).
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
