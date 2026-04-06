<?php

namespace App\Http\Controllers\Api\V1;

use App\Factories\PaymentServiceFactory;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Http\Resources\Api\V1\SubscriptionResource;
use App\Mail\SendPasswordMail;
use App\Mail\SendUnsubscriptionMail;
use App\Models\Influencer;
use App\Models\ReferralVisit;
use App\Models\User;
use App\Services\NetworkPaymentService;
use App\Services\OrangeMoneyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Str;
use App\Helpers\PhoneHelper;
use App\Models\PaymentUserDetail;
use App\Rules\ForbiddenNameKeyword;

class PaymentController extends Controller
{
    protected $networkPaymentService;
    protected OrangeMoneyService $orangeMoney;

    public function __construct(NetworkPaymentService $networkPaymentService, OrangeMoneyService $orangeMoney)
    {
        $this->networkPaymentService = $networkPaymentService;
        $this->orangeMoney = $orangeMoney;
    }

    public function getAllPayments(Request $request)
    {
        config(['paytabs.region' => 'JOR']);


        // Authenticate user using Sanctum (or similar)
        $user = auth('sanctum')->user();


        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Retrieve all payments for the logged-in user with related cart items and their details
        $payments = Payment::where('user_id', $user->id)
            ->where('status', 'approved')
            ->with(['cartItems'])
            ->get();

        return response()->json(['payments' => $payments], 200);
    }

    public function handlePayment(Request $request)
    {
        // $paymentMethod = $request->input('payment_method');
        $paymentMethod = "Network";
        try {
            // Before handling payment, mark any cart items whose related item/quick is disabled
            $user = auth('sanctum')->user();
            if ($user) {
                Cart::with('item', 'quickContribute')
                    ->where('user_id', $user->id)
                    ->where('is_paid', 0)
                    ->get()
                    ->each(function ($cartItem) {
                        if($cartItem->model_type == "App\Models\Item"){
                            if($cartItem->item->status == 0){
                                $cartItem->update(['is_paid' => -2]);
                            }
                        } else if($cartItem->model_type == "App\Models\QuickContribution"){
                            if($cartItem->quickContribute->status == 0){
                                $cartItem->update(['is_paid' => -2]);
                            }
                        }
                    });
            } elseif ($request->temp_id) {
                Cart::with('item', 'quickContribute')
                    ->where('temp_id', $request->temp_id)
                    ->where('is_paid', 0)
                    ->get()
                    ->each(function ($cartItem) {
                        if($cartItem->model_type == "App\Models\Item"){
                            if($cartItem->item->status == 0){
                                $cartItem->update(['is_paid' => -2]);
                            }
                        } else if($cartItem->model_type == "App\Models\QuickContribution"){
                            if($cartItem->quickContribute->status == 0){
                                $cartItem->update(['is_paid' => -2]);
                            }
                        }
                    });
            }

            $paymentService = PaymentServiceFactory::create($paymentMethod); // Use the factory
            return $paymentService->handlePayment($request); // Process payment with the correct service
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function paymentRedirect(Request $request)
    {
        $cartId = $request->query('cart_id');

        try {
            $result = $this->networkPaymentService->handlePaymentRedirect($cartId);

            return $this->redirectWithStatus($result['locale'], $result['status'], $result['message'], null, $cartId);

        } catch (\Throwable $e) {
            $locale = 'en';
            if (isset($result['locale'])) {
                $locale = $result['locale'];
            }
            $path = ($locale === 'ar') ? '/السلة' : '/basket';

            return $this->redirectWithStatus($locale, 'failed', $e->getMessage(), $path);
        }
    }

    /**
     * Helper to build redirect URL
     */
    private function redirectWithStatus(string $locale, string $status, string $message = null, string $path = null, $cart_id = null)
    {
        $baseUrl = rtrim(env('APP_URL_FRONTEND'), '/');

        $url = "{$baseUrl}/{$locale}{$path}?payment={$status}";
        if ($message) {
            $url .= '&message=' . urlencode($message);
        }
        if ($cart_id) {
            $url .= '&cart_id=' . $cart_id;
        }

        return redirect($url);
    }

    public function processPayment(Request $request)
    {
        config(['paytabs.region' => 'JOR']);
        config(['paytabs.currency' => 'JOD']);
        $temp_id = $request->get('temp_id') ?? '';
        $locale = app()->getLocale();
        // Authenticate user using Sanctum (or similar)
        $user = auth('sanctum')->user();

        if (!$user) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        // Extract user details
        $name = $user->first_name . ' ' . $user->last_name;
        $phone = $user->phone;
        $street1 = $user->street;
        $city = $user->city;
        $state = $user->state;
        $country = $user->country;
        $zip = $user->zip;

        // Email from the request
        $email = $user->email;

        // Fetch all unpaid items for the authenticated user
        $cartItems = Cart::where('user_id', $user->id)
            ->where('is_paid', false)
            ->get();

        // Calculate the total amount and prepare item details
        $cart_amount = 0;
        $items = [];
        $checkSubscription = 0;
        $desc_payment_meps = '';
        foreach ($cartItems as $key => $cartItem) {
            $cart_amount += $cartItem->price;
            $items[] = [
                'item_id' => $cartItem->item_id,
                'price' => $cartItem->price,
                'type' => $cartItem->type,
            ];

            if ($cartItem->model_type == "App\Models\QuickContribution") {
                $desc_payment_meps .= ($key + 1) . "-" . $cartItem->quickContribute->getLocalizationTitle() . " (" . $cartItem->type . ") ";
            } else if ($cartItem->model_type == "App\Models\Item") {
                $desc_payment_meps .= ($key + 1) . "-" . $cartItem->item->getLocalizationTitle() . " (" . $cartItem->type . ") ";
            }

            if ($cartItem->type == 'monthly') {
                $checkSubscription = 1;
                // Add subscription entry
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

        if ($cart_amount <= 0) {
            return response()->json(['error' => 'Cart amount must be greater than 0'], 400);
        }

        // Prepare payment details
        if ($checkSubscription) {
            $payment_method = 'all, -applepay';
        } else {
            $payment_method = 'all';
        }

        $tran_type = 'sale';
        // $tran_class = 'recurring';
        $tran_class = 'ecom';
        $cart_id = 'CART' . time();

        $cart_description = $desc_payment_meps;
        $return = env('APP_URL_BACKEND') . "api/v1/payment/callback?locale=" . $locale . "&invoice=" . $cart_id . "&subscription=" . $checkSubscription;
        $callback = env('APP_URL_BACKEND') . "api/v1/payment/callback?locale=" . $locale . "&invoice=" . $cart_id . "&subscription=" . $checkSubscription;
        $language = $locale;
        $ip = $request->ip();

        $referrer_id = null;
        if ($request->code) {
            $influencer = Influencer::where('code', $request->code)->first();
            if ($influencer) {
                if (!$influencer->isExpired()) {
                    $referrer = ReferralVisit::where('user_id', $user->id)->orWhere('temp_id', $temp_id)->where('referrer_id', $influencer->id)->first();
                    $referrer_id = $influencer->id;
                }
            }
        }
        try {
            $pay = paypage::sendPaymentCode($payment_method)
                ->sendTransaction($tran_type, $tran_class)
                ->sendCart($cart_id, $cart_amount, $cart_description)
                ->sendCustomerDetails($name, $email, $phone, $street1, $city, $state, $country, $zip, $ip)
                ->sendShippingDetails($name, $email, $phone, $street1, $city, $state, $country, $zip, $ip)
                ->sendHideShipping(true)
                ->sendURLs($return, $callback)
                ->sendLanguage($language)
                ->sendFramed(false)
                ->sendTokinse(2)
                ->sendUserDefined(
                    [
                        "code" => $request->code,
                    ]
                )
                ->create_pay_page();

            // Use reflection to extract protected properties if necessary
            $reflection = new \ReflectionClass($pay);
            $statusCodeProperty = $reflection->getProperty('statusCode');
            $statusCodeProperty->setAccessible(true);
            $statusCode = $statusCodeProperty->getValue($pay);

            $targetUrlProperty = $reflection->getProperty('targetUrl');
            $targetUrlProperty->setAccessible(true);
            $targetUrl = $targetUrlProperty->getValue($pay);

            if ($statusCode == 302 && isset($targetUrl)) {
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'cart_id' => $cart_id,
                    'status' => 'initiated',
                    'amount' => $cart_amount,
                    'payment_type' => $payment_method,
                    'referrer_id' => $referrer_id,
                    'acquirer_message' => null,
                    'acquirer_rrn' => null,
                    'resp_code' => null,
                    'resp_message' => null,
                    'signature' => null,
                    'token' => null,
                    'tran_ref' => null,
                    'temp_id' => $temp_id,
                    'lang' => $locale
                ]);

                Cart::where(fn($query) => $query
                    ->where('temp_id', $temp_id)
                    ->orWhere('user_id', $user->id))
                    ->where('is_paid', false)
                    ->update(['payment_id' => $payment->id]);

                Subscription::where('user_id', $user->id)
                    ->where('status', 'inactive')
                    ->whereNull('payment_id')
                    ->update(['payment_id' => $payment->id]);

                return response()->json(['redirect_url' => $targetUrl], 200);
            } else {
                return response()->json(['error' => 'Failed to initiate payment'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleCallbackGet(Request $request)
    {
        Log::info('PayTabs Callback Data:', $request->all());
        $local = $request->get("locale");
        $cart_id = $request->get("invoice");
    }

    public function handleCallback(Request $request)
    {
        Log::info('PayTabs Callback Data:', $request->all()); // Log the callback data
        $data = $request->all();
        Log::info('PayTabs Callback Data:', $data); // Log the callback data

        $response_data = [];

        if (isset($data["payment_result"]) && count($data["payment_result"]) > 0) {
            $response_data['acquirerMessage'] = isset($data['payment_result']['acquirer_message']) ? $data['payment_result']['acquirer_message'] : '';
            $response_data['acquirerRRN'] = $data['payment_result']['acquirer_rrn'];
            $response_data['cartId'] = $data["cart_id"];
            $response_data['customerEmail'] = $data["customer_details"]['email'];
            $response_data['respCode'] = $data['payment_result']['response_code'];
            $response_data['respMessage'] = $data['payment_result']['response_message'];
            $response_data['respStatus'] = $data['payment_result']['response_status'];
            $response_data['signature'] = '';
            $response_data['token'] = $data["token"] ?? "apple";
            $response_data['tranRef'] = $data['tran_ref'];
            $response_data['locale'] = $data["locale"];
            $response_data['invoice'] = $data["cart_id"];
            $response_data['subscription'] = $data["subscription"];
        } else {
            $response_data = $data;
        }

        // Find the payment by the transaction reference (tran_ref)
        $payment = Payment::where('cart_id', $response_data['cartId'])->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        $locale = $payment->lang;

        if (isset($response_data['respStatus']) && $response_data['respStatus'] == 'A') {

            // Update is_paid for all carts
            Cart::where('payment_id', $payment->id)
                ->where('user_id', $payment->user_id)
                ->where('is_paid', false)
                ->update(['is_paid' => true]);

            // Activate subscription
            Subscription::where('payment_id', $payment->id)
                ->where('status', 'inactive')
                ->whereNull('end_date')
                ->update(['status' => 'active', 'end_date' => now()->addMonth()]);

            // Update the payment status to 'approved'
            $payment->update([
                'tran_ref' => $response_data['tranRef'],
                'status' => 'approved',
                'acquirer_message' => $response_data['acquirerMessage'] ?? null,
                'acquirer_rrn' => $response_data['acquirerRRN'] ?? null,
                'resp_code' => $response_data['respCode'] ?? null,
                'resp_message' => $response_data['respMessage'] ?? null,
            ]);

            if (!empty($response_data['signature'])) {
                $payment->update([
                    'signature' => $response_data['signature'],
                ]);
            }
            if (!empty($response_data['token'])) {
                $payment->update([
                    'token' => $response_data['token'],
                ]);
            }

            return redirect(env('APP_URL_FRONTEND') . $locale . '?payment=success');
        } else {
            if ($locale == "ar") {
                return redirect(env('APP_URL_FRONTEND') . $locale . '/السلة' . '?payment=failed&message=' . $response_data['respMessage']);
            } else {
                return redirect(env('APP_URL_FRONTEND') . $locale . '/basket?payment=failed&message=' . $response_data['respMessage']);
            }
        }
    }

    public function renew()
    {
        $profileId = config('paytabs.profile_id');
        $apiUrl = 'https://secure-jordan.paytabs.com/payment/request';
        $authorizationKey = config('paytabs.server_key');
        // Get subscriptions where end_date <= today

        $subscriptions = Subscription::where('end_date', '<=', now())->where('status', 'active')->get();

        foreach ($subscriptions as $subscription) {
            $cartId = $subscription->payment->cart_id;
            $amount = $subscription->price;
            $currency = 'JOD'; // Adjust based on your logic
            $token = $subscription->payment->token; // Assuming the payment token is stored in the related payment model.
            // Prepare API payload
            $payload = [
                'profile_id' => $profileId,
                'tran_type' => 'sale',
                'tran_class' => 'recurring',
                'cart_description' => 'Renewal for subscription ' . $subscription->id,
                'cart_id' => $cartId,
                'cart_amount' => $amount,
                'cart_currency' => $currency,
                'token' => $token,
            ];

            // Call the PayTabs API
            $response = Http::withHeaders([
                'Authorization' => $authorizationKey,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);
            if ($response->successful()) {
                // Update subscription's end_date to next month
                $subscription->update([
                    'end_date' => now()->addMonth(),
                ]);

                return response()->json(['message' => 'Payment successful', 'data' => $subscription->id]);

            } else {
                return response()->json(['message' => "Failed to renew subscription {$subscription->id}"]);

                // $this->error("Failed to renew subscription {$subscription->id}. Error: " . $response->body());
            }
        }
        return response()->json(['message' => "Subscription renewal process completed"]);

        // $this->info('Subscription renewal process completed.');
    }


    public function deactivate(Request $request, $id)
    {
        $user = auth('sanctum')->user();
        if ($user) {


            $subscription = Subscription::find($id);

            if (!$subscription) {
                return response()->json(['message' => 'Subscription not found'], 404);
            }

            $subscription->status = 'inactive';

            $subscription->save();
            Mail::to($subscription->user->email)->cc(env('BILLS_EMAIL'))->send(new SendUnsubscriptionMail($subscription));
            return response()->json([
                'message' => "Subscription status updated to {$subscription->status}",
                'subscription' => $subscription,
            ]);
        }
    }

    public function getPaymentHistorySubscriptions(Request $request)
    {
        // Authenticate user using Sanctum (or similar)
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Retrieve all payments for the logged-in user
        $payments = Subscription::where('user_id', $user->id)
            ->with(['payment']) // Load related cart items
            ->get();

        $subscriptionPayments = [];

        foreach ($payments as $payment) {
            if ($payment->payment->status == "approved") {
                $subscriptionPayments[] = $payment;
            }
        }
        return SubscriptionResource::collection($subscriptionPayments);
    }

    public function create_guest(Request $request)
    {
        $locale = $request->query('locale', app()->getLocale());
        app()->setLocale($locale);
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword()],
            'last_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword()],
            'phone' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }

        $temp_id = $request->temp_id ?? null;

        // Check if the user already exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Generate a random password
            $password = Str::random(10);

            // Create the user account with the provided data
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($password), // Hash the password
                'country' => null,
                'role' => 2, // Assign role to collection team
            ]);

            // Send the password to the user's email
            Mail::to($request->email)->send(new SendPasswordMail($user, $password));
        } else {
            if (empty($user->phone)) {
                $user->update([
                    "phone" => $request->phone
                ]);
            }
        }

        if ($temp_id) {
            Cart::where('is_paid', 0)->where('user_id', $user->id)->where('temp_id', '<>', $temp_id)->update(["is_paid" => -1]);
            Cart::where("temp_id", $temp_id)->where('is_paid', 0)->update(["user_id" => $user->id]);
        }

        // Return the response with the user details and the generated token
        return response()->json([
            'user' => $user,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone
        ], 201);
    }

    public function getCountry($countryPhoneCode)
    {
        // Path to the JSON file in the public folder
        $filePath = public_path('countries.json');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return null;
        }

        // Fetch and decode the JSON file
        $countriesList = json_decode(file_get_contents($filePath), true);

        if (!$countryPhoneCode) {
            return null;
        }

        // Find the country based on the country code
        $country = collect($countriesList)->firstWhere('phone_code', $countryPhoneCode);

        if ($country) {
            return $country['country_name_english'];
        }

        return null;
    }

    public function initiatePhoneOM(Request $request)
    {
        $temp_id = $request->get('temp_id') ?? '';
        $locale = app()->getLocale();
        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone_number = $request->phone_number;
        $orange_number = $request->orange_number;

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
            $transactionData = [
                'SenderWallet' => $orange_number,
                'Amount'       => $cartAmount,
                'isConfirmed'  => false,
                'OTP'          => '',
            ];

            $prepared = $this->orangeMoney->prepareTransaction($transactionData);

            $response = $this->orangeMoney->sendTransaction($prepared);

            $data = $response['response']; // this is the decoded JSON from Orange API
            
            if (!$data['isSuccess']) {
                if(isset($data["errors"]) && isset($data["errors"][0]) && isset($data["errors"][0]["description"]) && $locale == "en"){
                    $err_desc = $data["errors"][0]["description"];
                } 
                elseif(isset($data["errors"]) && isset($data["errors"][0]) && isset($data["errors"][0]["descriptionAr"]) && $locale == "ar"){
                    $err_desc = $data["errors"][0]["descriptionAr"];
                } 
                else {
                    $err_desc = $data['errorDescription'];
                }
                return response()->json([
                    'success' => false,
                    'message' => $err_desc ?? 'Unknown error occurred',
                    'code' => $data['errorCode'] ?? null
                ], 422);
            } else {
                $cartId = 'OM' . $user->id . time();
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'cart_id' => $cartId,
                    'status' => 'initiated',
                    'amount' => $cartAmount,
                    'payment_type' => 'Orange Money',
                    'bank_issuer' => "Orange Money Jordan",
                    'referrer_id' => $referrerId,
                    'signature' => null,
                    'temp_id' => $temp_id,
                    'lang' => $locale,
                    'cliq_number' => $orange_number
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
                    'success' => true,
                    'id' => $payment->id
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function payOrangeMoney(Request $request){
        $temp_id = $request->get('temp_id') ?? '';
        $locale = app()->getLocale();
        $payment_id = $request->id ?? '';
        $otp = $request->otp ?? '';
        
        $payment = Payment::find($payment_id);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        try {
            $transactionData = [
                'SenderWallet' => $payment->cliq_number,
                'Amount'       => $payment->amount,
                'isConfirmed'  => true,
                'OTP'          => $otp,
            ];

            $prepared = $this->orangeMoney->prepareTransaction($transactionData);

            $response = $this->orangeMoney->sendTransaction($prepared);

            $data = $response['response']; // this is the decoded JSON from Orange API

            if (!$data['isSuccess']) {
                if(isset($data["errors"]) && isset($data["errors"][0]) && isset($data["errors"][0]["description"]) && $locale == "en"){
                    $err_desc = $data["errors"][0]["description"];
                } 
                elseif(isset($data["errors"]) && isset($data["errors"][0]) && isset($data["errors"][0]["descriptionAr"]) && $locale == "ar"){
                    $err_desc = $data["errors"][0]["descriptionAr"];
                } else {
                    $err_desc = $data['errorDescription'];
                }
                return response()->json([
                    'success' => false,
                    'message' => $err_desc ?? 'Unknown error occurred',
                    'code' => $data['errorCode'] ?? null
                ], 422);
            } else {
                $transactionReference = $data["transactionReference"];
                $TransactionId = $data["TransactionId"];
                $payment->status = "approved";
                $payment->response = $data;
                $payment->cart_id = $transactionReference;
                $payment->signature = $TransactionId;
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

                return response()->json([
                    'cart_id' => $payment->cart_id,
                    'success' => true
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMetaPurchase(Request $request, $cart_id){
        try {
            $payment = Payment::where('cart_id', $cart_id)->first();
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                ], 404);
            }
            
            $payment->purchase_meta_pixel = true;
            $payment->save();

            return response()->json([
                'success' => true
            ], 200);

        } catch (\Exception $e) {
            // You can log the exception for debugging
            \Log::error('Error fetching payment status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payment details',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    public function getPaymentStatusDetails($cart_id)
    {
        try {
            $payment = Payment::where('cart_id', $cart_id)->first();
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new PaymentResource($payment),
            ], 200);

        } catch (\Exception $e) {
            // You can log the exception for debugging
            \Log::error('Error fetching payment status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching payment details',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }
}
