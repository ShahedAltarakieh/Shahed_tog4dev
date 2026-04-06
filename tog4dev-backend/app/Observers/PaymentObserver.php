<?php

namespace App\Observers;

use App\Models\Payment;
use App\Jobs\OdooJobs\SendPaymentToOdooJob;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        // if($payment->status == "approved"){
        //     SendPaymentToOdooJob::dispatch($payment->id);
        // }
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // if($payment->status == "approved"){
        //     SendPaymentToOdooJob::dispatch($payment->id);
        // }
    }
} 