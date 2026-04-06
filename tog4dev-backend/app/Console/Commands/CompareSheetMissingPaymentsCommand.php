<?php

namespace App\Console\Commands;

use App\Jobs\CompareSheetMissingPaymentsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompareSheetMissingPaymentsCommand extends Command
{
    protected $signature = 'app:compare-sheet-missing-payments';

    protected $description = 'Write all compare-sheet.xlsx data rows plus payment_cart_id, payment_id, payment_amount from Payment lookup';

    public function handle(): int
    {
        if (! is_file(public_path('compare-sheet.xlsx'))) {
            $this->error('compare-sheet.xlsx was not found in public/.');

            return self::FAILURE;
        }

        $job = new CompareSheetMissingPaymentsJob();
        try {
            dispatch_sync($job);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $relative = CompareSheetMissingPaymentsJob::OUTPUT_PATH;
        $this->info('Wrote: '.Storage::disk(CompareSheetMissingPaymentsJob::OUTPUT_DISK)->path($relative));
        $this->info('Rows written: '.$job->exportedRowCount);

        return self::SUCCESS;
    }
}
