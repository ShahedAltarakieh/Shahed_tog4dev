<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\ErrorEmail;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\PaymentUserDetail;
use Illuminate\Console\Command;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Services\NetworkPaymentService;

class RenewSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-renew-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew payments subscriptions';

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
        $today = Carbon::today();
        $threeDaysAgo = $today->copy()->subDays(3);
        $subscriptions = Subscription::where('status', 'active')
            ->where("send_reminder", 1)
            ->whereDate('end_date', '>=', $threeDaysAgo)
            ->whereDate('end_date', '<=', $today)
            ->whereHas('payment', function($query) {
                $query->where('payment_type', 'Network');
            })
        ->get();

        foreach ($subscriptions as $subscription) {
            try {
                $locale = $subscription->payment->lang ?? 'en';
                app()->setLocale($locale);
                
                $desc = $subscription->title_en." - Subscription renewal";

                $cartId = $subscription->payment->cart_id;
                $new_cart_id = 'N' . $subscription->user_id . time();
                $amount = $subscription->price;
                $firstTime = $subscription->first_time;
                $token = $subscription->payment->token;

                // Process the recurring payment
                $response = $this->networkPaymentService->processRecurringPayment(
                    $cartId,
                    $new_cart_id,
                    $amount,
                    $token,
                    $firstTime,  // not first time,
                    $desc
                );

                if (isset($response['order']) && isset($response["order"]["status"]) && $response["order"]["status"] == "CAPTURED") {
                    // Create new payment record
                    $payment = Payment::create([
                        'user_id' => $subscription->user_id,
                        'cart_id' => $new_cart_id,
                        'status' => 'approved',
                        'amount' => $amount,
                        'referrer_id' => $subscription->payment->referrer_id,
                        'payment_type' => 'Network',
                        'acquirer_message' => $response['response']['gatewayCode'] ?? null,
                        'resp_code' => $response['response']['acquirerCode'] ?? null,
                        'resp_message' => $response['response']['acquirerMessage'] ?? null,
                        'token' => $token,
                        'tran_ref' => $response['transaction']['id'] ?? null,
                        'lang' => $locale,
                        'send_email' => 0,
                        'subscription_id' => $subscription->id,
                        "response" => json_encode($response),
                        "country" => $subscription->country,
                        'name_on_card' => $subscription->payment->name_on_card,
                        'bank_issuer' => $subscription->payment->bank_issuer,
                    ]);

                    if (!$payment) {
                        Mail::to(env('ERROR_EMAIL'))->send(new ErrorEmail(null, $subscription, true));
                        continue;
                    }

                    // Create new cart entry
                    $cart = Cart::where('payment_id', $subscription->payment_id)
                        ->where('model_type', $subscription->model_type)
                        ->where('item_id', $subscription->item_id)
                        ->where('user_id', $subscription->user_id)
                        ->where('type', 'monthly')
                        ->where('is_paid', 1)
                        ->first();

                    if ($cart) {
                        Cart::create([
                            'user_id' => $cart->user_id,
                            'model_type' => $cart->model_type,
                            'item_id' => $cart->item_id,
                            'price' => $cart->price,
                            'quantity' => $cart->quantity,
                            'type' => 'monthly',
                            'is_paid' => true,
                            'payment_id' => $payment->id,
                            'subscription_id' => $subscription->id,
                            'title' => $cart->title,
                            'title_en' => $cart->title_en,
                            'description' => $cart->description,
                            'description_en' => $cart->description_en,
                            'location' => $cart->location,
                            'location_en' => $cart->location_en,
                            'analyticـaccount_id' => $cart->analyticـaccount_id,
                            'has_beneficiary' => $cart->has_beneficiary ?? false,
                        ]);
                    }

                    // Update subscription end date
                    $subscription->update([
                        'end_date' => now()->addMonth(),
                    ]);

                    $payment_details = PaymentUserDetail::create([
                        'user_id' => $payment->user->id,
                        'payment_id' => $payment->id,
                        'first_name' => $payment->user->first_name,
                        'last_name' => $payment->user->last_name,
                        'email' => $payment->user->email,
                        "phone" => $payment->user->phone,
                        "country" => $payment->user->country,
                    ]);

                    $this->info("Subscription {$subscription->id} renewed successfully.");
                } else {
                    $errorMessage = $response['error']['explanation'] ?? json_encode($response);
                    $this->error("Failed to renew subscription {$subscription->id}. Error: " . $errorMessage);
                    Mail::to(env('ERROR_EMAIL'))->send(new ErrorEmail($errorMessage, $subscription, true));
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription {$subscription->id}: " . $e->getMessage());
                Mail::to(env('ERROR_EMAIL'))->send(new ErrorEmail($e->getMessage(), $subscription, true));
            }
        }

        $this->info('Subscription renewal process completed.');
    }
}
