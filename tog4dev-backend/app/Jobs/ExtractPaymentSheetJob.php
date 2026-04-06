<?php

namespace App\Jobs;

use App\Models\ExcelSheet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Imports\ExcelOrdersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ProcessPaymentsSheetToOrderJob;

class ExtractPaymentSheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sheet_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sheet_id)
    {
        $this->sheet_id = $sheet_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sheet = ExcelSheet::find($this->sheet_id);
        if($sheet){
            try {
                $sheet->status = 1;

                $media = $sheet->getFirstMedia('excel_sheets'); // 'excel_files' is the media collection name
                if ($media) {
                    // Get the full file path
                    $filePath = $media->getPath();
                    if ($this->validateExcelData($filePath)) {
                        Excel::import(new ExcelOrdersImport($sheet->id), $filePath);
                        $sheet->status = 2;
                        $sheet->save();
                        ProcessPaymentsSheetToOrderJob::dispatch($sheet->id)->delay(2);
                    }
                } else {
                    $sheet->status = -1; // no media found
                    $sheet->error_message = "no media found";
                    $sheet->save();
                }
                $sheet->save();
            } catch (\Exception $e) {
                $sheet->status = -1;
                $sheet->error_message = $e->getMessage();
                $sheet->save();
            }
        }
    }

    /**
     * Validate the data in the Excel file before importing.
     *
     * @param string $filePath
     * @return bool
     */
    private function validateExcelData($filePath)
    {
        return true;
        // Load the Excel file to read the data
        $data = Excel::toArray([], $filePath);

        // Assuming your data is in the first sheet
        $sheetData = $data[0];
        // Validate each row of data
        foreach ($sheetData as $row) {
            // Perform your validations here
            $validator = Validator::make($row, [
                'T4D Reference' => 'required|string',
                'Order ID' => 'required|integer',
                'Payment Status' => 'required|string|in:pending,completed,failed',  // Example of a status field
                'Total' => 'required|numeric|min:0',
                'Name' => 'required|string',
                'Customer Email' => 'required|email',
                'Customer Phone Number' => 'required|string',
                'Customer Address' => 'required|string',
                'Order Items' => 'required|string',  // Modify based on actual order items format
                'Payment Method' => 'required|string|in:credit card,paypal,bank transfer',
            ]);

            // If validation fails, return false and stop the import process
            if ($validator->fails()) {
                $this->error("Validation failed for row: " . json_encode($row));
                return false;
            }
        }

        // Return true if all data passed validation
        return true;
    }
}
