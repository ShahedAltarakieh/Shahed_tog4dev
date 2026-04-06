<?php

namespace App\Console\Commands;

use App\Jobs\OdooJobs\SendPaymentToOdooJob;
use App\Models\Payment;
use App\Models\User;
use App\Services\OdooService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RetryOdooPayments extends Command
{
    protected $signature = 'app:retry-odoo-payments';

    protected $description = 'Retry sending approved payments without an Odoo ID to the Odoo API';

    public function handle(): void
    {
        $lock = Cache::lock('retry-odoo-payments-lock', 300);

        if (! $lock->get()) {
            \Log::info('RetryOdooPayments cron job is already running.');

            return;
        }

        try {
            $payments = Payment::where('status', 'approved')
                ->whereNull('odoo_id')
                ->whereYear('created_at', '!=', 2024)
                ->take(50)
                ->get();

            if ($payments->isEmpty()) {
                $this->info('No approved payments with null odoo_id found.');

                return;
            }

            foreach ($payments as $payment) {
                try {
                    $user = User::find($payment->user_id);

                    if (! $user) {
                        $this->warn("User not found for payment {$payment->id}");
                        continue;
                    }

                    if ($user->odoo_id === null) {
                        $response = app(OdooService::class)->post('v1/addPartner', $user->toOdoo(true));

                        if (
                            isset($response['result']['status']) &&
                            $response['result']['status'] === 200 &&
                            isset($response['result']['data']['id'])
                        ) {
                            $odooId = $response['result']['data']['id'];
                            $user->odoo_id = $odooId;
                            $user->saveQuietly();
                        }
                    } else {
                        app(OdooService::class)->put('v1/updatePartner', $user->toOdoo(false));
                    }

                    SendPaymentToOdooJob::dispatch($payment->id)->delay(2);

                    // Small pause to avoid hammering external services.
                    usleep(200000); // 0.2 second
                } catch (\Throwable $e) {
                    \Log::error("Error retrying Odoo sync for payment {$payment->id}: {$e->getMessage()}", [
                        'payment_id' => $payment->id,
                        'exception' => $e,
                    ]);
                }
            }
        } finally {
            optional($lock)->release();
        }
    }
}

