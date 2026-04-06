<?php

namespace App\Services;

use App\Helpers\PhoneHelper;
use App\Models\Influencer;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\PaymentUserDetail;
use App\Models\ReferralVisit;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NetworkPaymentService implements PaymentGatewayInterface
{
    public function handlePayment(Request $request)
    {
        return $this->processNetworkPayment($request);
    }

    public function processNetworkPayment(Request $request)
    {
        $temp_id = $request->get('temp_id') ?? '';
        $locale = app()->getLocale();
        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone_number = $request->phone_number;

        $user = auth('sanctum')->user() ?: User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->where('is_paid', false)
            ->get();

        $cartAmount = 0;
        $cartDescription = '';

        foreach ($cartItems as $idx => $cartItem) {
            $cartAmount += $cartItem->price;

            if ($cartItem->model_type === "App\Models\QuickContribution") {
                $cartDescription .= ($idx + 1) . '-' . $cartItem->quickContribute->getLocalizationTitle() . ' (' . $cartItem->type . ") ";
            } else if ($cartItem->model_type === "App\Models\Item") {
                $cartDescription .= ($idx + 1) . '-' . $cartItem->item->getLocalizationTitle() . ' (' . $cartItem->type . ") ";
            }

            if ($cartItem->type === 'monthly') {
                Subscription::create([
                    'user_id' => $user->id,
                    'item_id' => $cartItem->item_id,
                    'subscription_id' => 'SUB' . time(),
                    'model_type' => $cartItem->model_type,
                    'price' => $cartItem->price,
                    'start_date' => now(),
                    'temp_id' => $temp_id,
                    'status' => 'inactive',
                    'title' => $cartItem->title,
                    'title_en' => $cartItem->title_en,
                    'description' => $cartItem->description,
                    'description_en' => $cartItem->description_en,
                    'location' => $cartItem->location,
                    'location_en' => $cartItem->location_en
                ]);
            }
        }

        if ($cartAmount <= 0) {
            return response()->json(['error' => 'Cart amount must be greater than 0'], 400);
        }

        $merchantId = config('network.merchant_id');
        $merchantPass = config('network.merchant_password');
        $merchantName = ($locale == "ar") ? "معاً للوساطة التجارية" : "Together for Intermediation Services";
        $description = $this->utf8ByteLimit(trim($cartDescription), 120);
        $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
        $apiVersion = 100;
        $cartId = 'N' . $user->id . time();
        $currency = config('network.currency', 'JOD');
        $reference = (string) Str::uuid();

        $payload = [
            'apiOperation' => 'INITIATE_CHECKOUT',
            'interaction' => [
                'operation' => 'PURCHASE',
                'merchant' => ['name' => $merchantName, 'url' => env('APP_URL_BACKEND') . 'api/v1/payment/receiveWebhook'],
                'returnUrl' => env('APP_URL_BACKEND') . 'api/v1/payment/receiveWebhook?cart_id=' . $cartId,
            ],
            'order' => [
                'id' => $cartId,
                'amount' => $cartAmount,
                'currency' => $currency,
                'reference' => $reference,
                'description' => $description ?: 'Cart purchase',
            ],
        ];

        $referrerId = null;
        if ($request->code) {
            $influencer = Influencer::where('code', $request->code)->first();
            if ($influencer && !$influencer->isExpired()) {
                $referrer = ReferralVisit::where(function ($q) use ($user, $temp_id) {
                    $q->where('user_id', $user->id)->orWhere('temp_id', $temp_id);
                })->where('referrer_id', $influencer->id)->first();
                $referrerId = $influencer->id;
            }
        }

        try {
            $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
                ->acceptJson()
                ->post("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/session", $payload);

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to initiate payment', 'details' => $response->json()], 400);
            }

            $data = $response->json();
            $sessionId = $data['session']['id'] ?? null;
            $successInd = $data['successIndicator'] ?? null;

            $payment = Payment::create([
                'user_id' => $user->id,
                'cart_id' => $cartId,
                'status' => 'initiated',
                'amount' => $cartAmount,
                'payment_type' => 'Network',
                'referrer_id' => $referrerId,
                'signature' => $sessionId,
                'temp_id' => $temp_id,
                'lang' => $locale,
            ]);

            Cart::where(fn($q) => $q->where('temp_id', $temp_id)->orWhere('user_id', $user->id))
                ->where('is_paid', false)
                ->update(['payment_id' => $payment->id]);

            Subscription::where('user_id', $user->id)
                ->where('status', 'inactive')
                ->whereNull('payment_id')
                ->update(['payment_id' => $payment->id]);

            $phone_list = PhoneHelper::getPhoneDetails($phone_number);
            $phoneNumber = $phone_list["phone"];
            $country = $phone_list["country"];

            PaymentUserDetail::updateOrCreate(
                ['payment_id' => $payment->id],
                [
                    'user_id' => $payment->user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email ?? null,
                    'phone' => $phoneNumber ?? null,
                    'country' => $country ?? null,
                ]
            );

            return response()->json([
                "url" => null,
                "payment_method" => "network",
                "session_id" => $sessionId
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function utf8ByteLimit(string $text, int $bytes = 127): string
    {
        return (strlen($text) <= $bytes) ? $text : mb_strimwidth($text, 0, $bytes, '', 'UTF-8');
    }

    public function handlePaymentRedirect(string $cartId)
    {
        $merchantId = config('network.merchant_id');
        $merchantPass = config('network.merchant_password');
        $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
        $apiVersion = 100;

        $payment = Payment::where('cart_id', $cartId)->first();

        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        $locale = $payment->lang ?? 'en';
        $sessionId = $payment->signature;

        $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->post("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/token", [
                'session' => ['id' => $sessionId]
            ]);

        if (!$response->successful()) {
            // throw new \Exception('Token API call failed: ' . json_encode($response->json()));
        }

        $data = $response->json();

        // Retrieve transaction details
        $transactionResponse = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->get("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/order/{$cartId}");

        if ($transactionResponse->successful()) {
            $transactionData = $transactionResponse->json();
            $transactionId = $transactionData['authentication']['3ds']['transactionId'] ?? null;

            if ($transactionId) {
                $payment->tran_ref = $transactionId;
            }
        }

        // Update payment fields
        $payment->response = $data;
        $payment->token = $data['token'] ?? null;
        $payment->country = $transactionData['billing']['address']['country'] ?? null;
        $payment->status = (($transactionData['result'] ?? '') === 'SUCCESS') ? 'approved' : 'failed';
        if(isset($transactionData["sourceOfFunds"]) && isset($transactionData["sourceOfFunds"]["provided"])){
            if(isset($transactionData["sourceOfFunds"]["provided"]["card"])){
                $card_info = $transactionData["sourceOfFunds"]["provided"]["card"];
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

        return [
            'locale' => $locale,
            'status' => 'success',
            'message' => '',
        ];
    }


    public function getToken(string $sessionId)
    {
        $merchantId = config('network.merchant_id');
        $merchantPass = config('network.merchant_password');
        $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
        $apiVersion = 100;

        $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->post("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/token", [
                'session' => ['id' => $sessionId]
            ]);
        return $response->json();
    }

    public function refundPayment(string $paymentId)
    {
        $merchantId = config('network.merchant_id');
        $merchantPass = config('network.merchant_password');
        $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
        $apiVersion = 100;

        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $orderId = $payment->cart_id;

        $transactionId = $payment->tran_ref;
        $amount = $payment->amount;

        $refundPayload = [
            'apiOperation' => 'REFUND',
            'transaction' => [
                'amount' => (string) $amount,
                'currency' => config('network.currency', 'JOD'),
            ],
        ];

        $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->contentType('application/json')
            ->put("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/order/{$orderId}/transaction/{$transactionId}", $refundPayload);

        if (!$response->successful()) {
            $errorDetails = json_encode($response->json());
            throw new \Exception("Failed to process refund: {$errorDetails}");
        }
        $payment->status = "refund";
        $payment->save();

        return $response->json();
    }

    private function getAuthOrderDetails(string $orderId): array
    {
        try {
            $merchantId = config('network.merchant_id');
            $merchantPass = config('network.merchant_password');   
            $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
            $apiVersion = 100;

            $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->get("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/order/{$orderId}");

            // if (!$response->successful()) {
            //     throw new \Exception("Failed to get order details: {$response->body()}");
            // }

            $data = $response->json();

            // Get first transaction with 3DS2 authentication
            $transaction = collect($data['transaction'] ?? [])
                ->first(fn($tx) => isset($tx['authentication']['3ds2']));

            if (!$transaction || !isset($transaction['authentication'])) {
                throw new \Exception('Authentication data not found in order');
            }

            $auth = $transaction['authentication'];
            return [
                'authentication' => [
                    '3ds' => array_intersect_key($auth['3ds'], [
                        'acsEci' => true,
                        'authenticationToken' => true,
                        'transactionId' => true
                    ]),
                    '3ds2' => array_intersect_key($auth['3ds2'], [
                        'acsReference' => true,
                        'acsTransactionId' => true,
                        'authenticationScheme' => true,
                        'dsReference' => true,
                        'protocolVersion' => true,
                        'transactionStatus' => true
                    ])
                ]
            ];
        } catch (\Throwable $e) {
            throw new \Exception("Failed to process order details: {$e->getMessage()}");
        }
    }

    public function getOrderDetails(string $orderId)
    {
        try {
            $merchantId = config('network.merchant_id');
            $merchantPass = config('network.merchant_password');   
            $apiBase = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
            $apiVersion = 100;

            $response = Http::withBasicAuth("merchant." . $merchantId, $merchantPass)
            ->acceptJson()
            ->get("{$apiBase}/api/rest/version/{$apiVersion}/merchant/{$merchantId}/order/{$orderId}");

            // if (!$response->successful()) {
            //     throw new \Exception("Failed to get order details: {$response->body()}");
            // }

            $data = $response->json();

            return $data;

        } catch (\Throwable $e) {
            throw new \Exception("Failed to process order details: {$e->getMessage()}");
        }
    }

    /**
     * Build the Network API URL
     *
     * @param string $endpoint
     * @return string
     */
    private function buildApiUrl(string $endpoint): string
    {
        $baseUrl = rtrim(config('network.base_url', 'https://test-network.mtf.gateway.mastercard.com'), '/');
        $merchantId = config('network.merchant_id');
        $version = config('network.api_version', 100);
        
        return "{$baseUrl}/api/rest/version/{$version}/merchant/{$merchantId}{$endpoint}";
    }

    public function processRecurringPayment($cartId, $new_cart_id, $amount, $token, $firstTime, $desc): array
    {
        try {
            $basePayload = [
                'apiOperation' => 'PAY',
                'order' => [
                    'amount' => (string) $amount,
                    'currency' => config('network.currency', 'JOD'),
                    'reference' => "REF-".$cartId."-".time(),
                    'description' => $desc
                ],
                'sourceOfFunds' => ['token' => $token],
                'agreement' => [
                    'id' => 'agre-' . $cartId,
                    'type' => 'RECURRING'
                ]
            ];

            if (!$firstTime) {
                $orderDetails = $this->getAuthOrderDetails($cartId);
                $basePayload['authentication'] = $orderDetails['authentication'];
                $basePayload['agreement'] += [
                    'paymentFrequency' => 'MONTHLY',
                    'expiryDate' => '2040-01-01',
                    'minimumDaysBetweenPayments' => '30',
                    'amountVariability' => 'VARIABLE'
                ];
            } else {
                $basePayload['transaction'] = ['source' => 'MERCHANT'];
            }

            Log::info('Network Payment URL:', 
                [
                    'url' =>  $this->buildApiUrl("/order/{$new_cart_id}/transaction/{$new_cart_id}")
                ]
            );
            Log::info('Network Payment Payload:', ['payload' => $basePayload]);

            $response = Http::withBasicAuth(
                "merchant." . config('network.merchant_id'),
                config('network.merchant_password')
            )
            ->acceptJson()
            ->put(
                $this->buildApiUrl("/order/{$new_cart_id}/transaction/{$new_cart_id}"),
                $basePayload
            );

            if (!$response->successful()) {
                throw new \Exception("Payment processing failed: {$response->body()}");
            }
            Log::info('Network Payment Payload:', ['payload' => $response->json()]);
            return $response->json();
        } catch (\Throwable $e) {
            throw new \Exception("Failed to process recurring payment: {$e->getMessage()}");
        }
    }

}
