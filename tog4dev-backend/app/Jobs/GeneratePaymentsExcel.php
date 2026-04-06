<?php

namespace App\Jobs;

use App\Exports\AllPaymentsExport;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePaymentsExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filePath;

    public function __construct($filePath = 'exports/payments.xlsx')
    {
        $this->filePath = $filePath;
    }

    public function handle(): void
    {
        Excel::store(new AllPaymentsExport, $this->filePath, 'public');
    }

}
