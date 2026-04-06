<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExcelSheet extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Specify the table name if it's not the plural form of the model
    protected $table = 'excel_sheets';

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = [
        'file_name',
        'error_message',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('excel_sheets')
            ->useDisk('public'); // Specify the disk if necessary
    }

    /**
     * Get the orders for the ExcelSheet.
     */
    public function orders()
    {
        return $this->hasMany(ExcelOrders::class, 'excel_sheet_id');
    }
}
