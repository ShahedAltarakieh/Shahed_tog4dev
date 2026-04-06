<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\OdooJobs\SendPaymentToOdooJob;
use App\Services\OdooService;

class SendApprovedPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Fetch approved payments from 2025-01-01 until now
        $payments = Payment::where('status', 'approved')
            ->where('created_at', '>=', '2025-01-01 00:00:00')
            ->where('created_at', '<', '2025-04-01 00:00:00')
            ->whereNull("odoo_id")
            ->orderBy('created_at', "asc")
            ->get();

        if ($payments->isEmpty()) {
            return;
        }

        foreach ($payments as $payment) {
            $user = User::find($payment->user_id);
            if($user->odoo_id == null){
                $response = app(OdooService::class)->post('v1/addPartner', $user->toOdoo(true));
                if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                    $odooId = $response['result']['data']['id'];
                    $user->odoo_id = $odooId;
                    $user->saveQuietly(); // prevents triggering updated observer
                }
            } else {
                app(OdooService::class)->put("v1/updatePartner", $user->toOdoo(false));
            }
            SendPaymentToOdooJob::dispatch($payment->id)->delay(2);
        }
    }
}
