<?php

namespace App\Jobs;

use App\Exports\CompareSheetMissingPaymentsExport;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class CompareSheetMissingPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const OUTPUT_DISK = 'public';

    public const OUTPUT_PATH = 'exports/compare-sheet-missing-payments.xlsx';

    public int $exportedRowCount = 0;


    public function handle(): void
    {
        $pathA = public_path('compare-sheet.xlsx');
        if (! is_file($pathA)) {
            throw new \RuntimeException('compare-sheet.xlsx was not found in public/.');
        }

        $raw = Excel::toArray([], $pathA)[0] ?? [];
        $headerRow = $raw[0] ?? [];
        $headings = array_map(static function ($cell) {
            return $cell === null ? '' : $cell;
        }, array_values($headerRow));
        $headings = array_merge($headings, [
            'payment_cart_id',
            'payment_id',
            'payment_amount',
            'Is Exists',
            'Correct value',
        ]);

        $sheetA = array_slice($raw, 1);
        $outputRows = [];
        foreach ($sheetA as $row) {
            $line = $row;

            $order_id = $row[1];
            $payment = null;
            if ($order_id !== null && $order_id !== '') {
                $payment = Payment::where('cart_id', 'like', '%'.$order_id.'%')->first();
            }

            if($payment){
                $line[] = $payment?->cart_id ?? '';
                $line[] = $payment?->id ?? '';
                $line[] = $payment->amount;
                $line[] = 'Yes';
                $line[] = $payment->amount == $row[6] ? 'Yes' : 'No';
            } else {
                $line[] = '';
                $line[] = '';
                $line[] = '';
                $line[] = 'No';
                $line[] = 'No';
            }
            $outputRows[] = $line;
        }

        Excel::store(
            new CompareSheetMissingPaymentsExport($headings, $outputRows),
            self::OUTPUT_PATH,
            self::OUTPUT_DISK
        );
    }
}
