<?php

namespace App\Imports;

use App\Models\ExcelOrders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // If the file has headings
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class ExcelOrdersImport implements ToModel, WithHeadingRow
{
    protected $sheetId;

    public function __construct($sheetId)
    {
        $this->sheetId = $sheetId; // Save the sheetId to be used in each row
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            if(!$row["order_id"]){
                return null;
            }
            if (isset($row['created_at'])) {
                $createdAtValue = $row['created_at'];

                if (is_numeric($createdAtValue)) {
                    $createdAt = Carbon::instance(Date::excelToDateTimeObject($createdAtValue))->format('Y-m-d H:i:s');
                } else {
                    try {
                        // First try: d/m/Y
                        $createdAt = Carbon::createFromFormat('d/m/Y', $createdAtValue)->format('Y-m-d H:i:s');
                    } catch (\Exception $e1) {
                        try {
                            // Fallback: Y-m-d
                            $createdAt = Carbon::createFromFormat('Y-m-d', $createdAtValue)->format('Y-m-d H:i:s');
                        } catch (\Exception $e2) {
                            // Final fallback: parse freely
                            $createdAt = Carbon::parse($createdAtValue)->format('Y-m-d H:i:s');
                        }
                    }
                }
            } else {
                $createdAt = null;
            }

            $order_id = trim(str_replace(["Z -", "Q -"], "", $row["order_id"]));
            $phone = trim(str_replace(["+", "=", " ", ":"], "", $row["number"]));
            
            if(!$order_id){
                return null;
            }

            return new ExcelOrders([
                't4d_reference' => $row["t4d_reference"] ?? null,
                'order_id' => $order_id ?? null,
                'created_order_at' => $createdAt ?? null,
                'payment_status' => $row["payment_status"] ?? null,
                'total' => $row["total"] ?? null,
                'name' => $row["name"] ?? null,
                'customer_email' => $row["email"] ?? null,
                'customer_phone_number' => $phone ?? null,
                'customer_address' => $row["customer_address"] ?? null,
                'order_items' => $row["order_items"] ?? null,
                'payment_method' => $row["payment_method"] ?? null,
                'lang' => $row["language"] ?? null,
                'inf_id' => $row["inf_id"] ?? null,
                'inf_name' => $row["inf_name"] ?? null,
                'excel_sheet_id' => $this->sheetId,
            ]);

        } catch (Exception $e) {
            // Log the error with context
            Log::error('Failed to import Excel row', [
                'row' => $row,
                'error' => $e->getMessage(),
            ]);

            // Optionally, return null to skip this row
            return null;
        }
    }

}