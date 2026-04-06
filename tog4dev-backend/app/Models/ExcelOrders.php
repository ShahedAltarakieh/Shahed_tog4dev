<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelOrders extends Model
{
    use HasFactory;

    // Define the table name if it is not the plural form of the model
    protected $table = 'excel_sheet_orders';

    // Specify the fillable attributes to allow mass assignment
    protected $fillable = [
        't4d_reference',
        'order_id',
        'created_order_at',
        'payment_status',
        'total',
        'name',
        'customer_email',
        'customer_phone_number',
        'customer_address',
        'order_items',
        'payment_method',
        'excel_sheet_id',
        'status',
        'lang',
        'inf_id',
        'inf_name'
    ];

    /**
     * Get the ExcelSheet that owns the Order.
     */
    public function excelSheet()
    {
        return $this->belongsTo(ExcelSheet::class, 'excel_sheet_id');
    }
}
