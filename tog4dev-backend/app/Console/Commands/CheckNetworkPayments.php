<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Console\Command;
use App\Services\NetworkPaymentService;

class CheckNetworkPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-network-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if network payments not updated';

    protected $networkPaymentService;

    public function __construct(NetworkPaymentService $networkPaymentService)
    {
        parent::__construct();
        $this->networkPaymentService = $networkPaymentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
	$payments = Payment::where('status', '<>', 'approved')
        ->where('retry_fetch_response', false)
        ->where('created_at', '<', now()->subMinutes(2))
        ->orderBy('id', 'desc')
        ->where('payment_type', 'network')
        ->get();

    foreach ($payments as $key => $payment) {
            $response = $this->networkPaymentService->getOrderDetails($payment->cart_id);
            if(isset($response["result"]) && strtolower($response["result"]) == "success"){
                if(strtolower($response["status"]) == "captured"){
                    $payment->response = $response;
                    $tokenResponse = $this->networkPaymentService->getToken($payment->signature);
                    $payment->token = $tokenResponse['token'] ?? null;
                    $payment->country = $response['billing']['address']['country'] ?? null;
                    $payment->status = 'approved';
                    $payment->retry_fetch_response = true;

                    if(isset($response["sourceOfFunds"]) && isset($response["sourceOfFunds"]["provided"])){
                        if(isset($response["sourceOfFunds"]["provided"]["card"])){
                            $card_info = $response["sourceOfFunds"]["provided"]["card"];
                            $nameOnCard = isset($card_info["nameOnCard"]) ? $card_info["nameOnCard"] : '';
                            $issuer = isset($card_info["issuer"]) ? $card_info["issuer"] : '';
                            if(!empty($nameOnCard)){
                                $payment->name_on_card = $nameOnCard;
                            }
                            if(!empty($issuer)){
                                $payment->bank_issuer = $issuer;
                            }
                        }
                    }

                    $payment->save();

                    // Mark cart items as paid
                    Cart::where('payment_id', $payment->id)
                        ->where('user_id', $payment->user_id)
                        ->where('is_paid', false)
                        ->update(['is_paid' => true]);

                    // Activate subscriptions
                    Subscription::where('payment_id', $payment->id)
                        ->where('status', 'inactive')
                        ->whereNull('end_date')
                        ->update(['status' => 'active', 'end_date' => now()->addMonth()]);
                } else {
                    $payment->retry_fetch_response = true;
                    $payment->save();
                }
            } else {
                $payment->retry_fetch_response = true;
                $payment->save();
            }
        }
    }
}
