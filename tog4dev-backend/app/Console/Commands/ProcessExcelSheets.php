<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExcelSheet;
use Illuminate\Support\Facades\Cache;
use App\Imports\ExcelOrdersImport;
use Maatwebsite\Excel\Facades\Excel;

class ProcessExcelSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-excel-sheets';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Excel sheets to insert into excel orders';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Using Cache to create a mutex lock manually if needed
        $lockKey = 'process_excel_files_lock';

        if (Cache::has($lockKey)) {
            $this->info('Process is already running, skipping this execution.');
            return;
        }

        // Lock the job to prevent overlapping
        Cache::put($lockKey, true, 10); // Lock for 10 minutes
        
        // Fetch ExcelSheet records with status 0
        $sheets = ExcelSheet::where('status', 0)->get();
        foreach($sheets as $sheet){
            try {
                $sheet->status = 1; // Status set to '1' to indicate processing
                $sheet->save();

                $media = $sheet->getFirstMedia('excel_sheets'); // 'excel_files' is the media collection name
                
                if ($media) {
                    // Get the full file path
                    $filePath = $media->getPath();
                    if ($this->validateExcelData($filePath)) {
                        Excel::import(new ExcelOrdersImport($sheet->id), $filePath);
                    
                        // After processing, you can update the status to a different value if needed
                        $sheet->status = 2; // For example, set status to 2 for completed processing
                        $sheet->save();
    
                        $this->info("Successfully processed: " . $sheet->file_name);
                    }
                } else {
                    $this->error("No media found for order: " . $sheet->file_name);
                }
            } catch (\Exception $e) {
                $this->error("Error processing file: " . $sheet->file_name . " - " . $e->getMessage());
            } finally {
                // Release the lock after the process finishes
                Cache::forget($lockKey);
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
