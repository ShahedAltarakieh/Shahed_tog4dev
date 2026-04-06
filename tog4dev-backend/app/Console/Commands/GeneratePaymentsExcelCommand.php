<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GeneratePaymentsExcel;

class GeneratePaymentsExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-payments-excel-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GeneratePaymentsExcel::dispatch();

        $this->info("Excel export job dispatched. File will be saved to: storage/app/public");
    }
}
