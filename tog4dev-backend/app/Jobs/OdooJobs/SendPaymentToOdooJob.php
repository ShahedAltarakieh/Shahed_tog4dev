<?php

namespace App\Jobs\OdooJobs;

use App\Models\Payment;
use App\Services\OdooService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPaymentToOdooJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $paymentId;

    /**
     * Create a new job instance.
     */
    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the full model with relations
        $payment = Payment::with('cartItems', 'subscriptions')->find($this->paymentId);

        if (!$payment) {
            return;
        }
        
        try {
            Log::error('=================================');
            Log::error('Data to send:', $payment->toOdoo());
            Log::error('=================================');
            $response = app(OdooService::class)->post('v1/addPayment', $payment->toOdoo());
            if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                $odooId = $response['result']['data']['id'];
                $payment->odoo_id = $odooId;
                $payment->saveQuietly();
            } else {
                Log::error('Odoo Error:', $response->json());
            }
        } catch (\Throwable $e) {
            Log::error('Odoo Exception: ' . $e->getMessage());
        }
    }
}
