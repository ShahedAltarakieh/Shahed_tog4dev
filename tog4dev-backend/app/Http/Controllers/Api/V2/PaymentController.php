<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\PaymentResource;
use App\Models\User;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\PriceOption;
use App\Models\PaymentUserDetail;
use App\Models\QuickContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Rules\ForbiddenNameKeyword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\PhoneHelper;

class PaymentController extends Controller
{
    /**
     * Get all Items.
     */
    public function index(Request $request)
    {
        try {
            // Get the optional category type from the request
            $perPage = $request->query('per-page', 50); // Default per page to 50
            $orderBy = $request->query('order', 'DESC'); // Default order to DESC

            // Start the query
            $query = Payment::where("status", "approved")->with(['cartItems', 'subscriptions']);

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $stories = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return PaymentResource::collection($stories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving payments.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $this->logRequest($request->all());
            
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => ['required', 'string', new ForbiddenNameKeyword()],
            'last_name' => ['required', 'string', new ForbiddenNameKeyword()],
            "phone" => "required",
            "paymentId" => "required",
            'cart_items' => 'required|array',
            'cart_items.*.type' => 'required|string|in:one_time,monthly',
            'cart_items.*.price' => 'required|numeric',
            'cart_items.*.item_id' => 'required|integer',
            'cart_items.*.analytic_id' => 'required|integer',
            'cart_items.*.model' => 'required|string',
            'cart_items.*.quantity' => 'required|integer',
            "bank_issuer" => "required|string",
            "name_on_card" => "required|string",
            "payment_method" => "required|integer",
            "payment_reference" => "nullable|string",
            "lang" => "nullable|string",
            'x-source-type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error', 'message' => $validator->errors()], 400);
        }
        
        $country = $request->country ?? null;
        $phone = $request->phone ?? null; 
        $phone = str_replace(" ","", $phone);

        if(!isset($country) || empty($country)){
            $phone_list = PhoneHelper::getPhoneDetails($phone);
            $phone = $phone_list["phone"];
            $country = $phone_list["country"];
        }

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $phone,
                'password' => bcrypt('defaultPassword'),
                'country' => $country ?? null,
                'role' => 2,
                "need_sync" => 0
            ]
        );

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $cartItems = [];
            $subscriptionItems = [];

            foreach ($request->cart_items as $item) {
                
                $totalAmount += $item['price'] * $item['quantity'];

                $model = $this->getModelType($item['model']);

                $title = $title_en = $description = $description_en = $location = $location_en = '';

                $has_beneficiary = false;
                if($model == "App\Models\Item"){
                    $data_item = Item::where("odoo_id", $item["item_id"])->first();
                    if($data_item){
                        $title = $data_item->title;
                        $title_en = $data_item->title_en;
                        $description = $data_item->description;
                        $description_en = $data_item->description_en;
                        $location = $data_item->location;
                        $location_en = $data_item->location_en;
                        $has_beneficiary = (bool) $data_item->has_beneficiary;
                    }
                } else if($model == "App\Models\QuickContribution"){
                    $data_item = QuickContribution::where("odoo_id", $item["item_id"])->first();
                    if($data_item){
                        $title = $data_item->title;
                        $title_en = $data_item->title_en;
                        $description = $data_item->description;
                        $description_en = $data_item->description_en;
                        $location = $data_item->location;
                        $location_en = $data_item->location_en;
                    }
                }

                $option_id = null;
                $optionData = null;
                if(isset($item["dropdown_id"])){
                    $option_id = $item["dropdown_id"];

                    $priceOption = PriceOption::find($item["dropdown_id"]);
                    if ($priceOption) {
                        $optionData = json_encode([
                            'd1_option' => $priceOption->d1_option,
                            'd1_option_en' => $priceOption->d1_option_en,
                            'd2_option' => $priceOption->d2_option,
                            'd2_option_en' => $priceOption->d2_option_en,
                        ]);
                    }
                }

                $cartItem = Cart::create([
                    'user_id' => $user->id,
                    'item_id' => $item['item_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'type' => $item['type'],
                    'model_type' => $model,
                    'is_paid' => 1,
                    'option_id' => $option_id,
                    'option_label' => $optionData,
                    'title' => $title,
                    'title_en' => $title_en,
                    'description' => $description,
                    'description_en' => $description_en,
                    'location' => $location,
                    'location_en' => $location_en,
                    'has_beneficiary' => $has_beneficiary,
                ]);

                if ($item['type'] === 'monthly') {
                    $subscription = Subscription::create([
                        'user_id' => $user->id,
                        'item_id' => $item['item_id'],
                        'subscription_id' => 'SUB' . time(),
                        'model_type' => $model,
                        'price' => $item['price'],
                        'start_date' => now(),
                        'end_date' => now()->addMonth(),
                        'status' => 'active',
                        'payment_id' => null,
                        'title' => $title,
                        'title_en' => $title_en,
                        'description' => $description,
                        'description_en' => $description_en,
                        'location' => $location,
                        'location_en' => $location_en,
                    ]);

                    $cartItem->update(['subscription_id' => $subscription->id]);

                    $subscriptionItems[] = $subscription;
                }
                $cartItems[] = $cartItem;
            }

            $payment_method = Setting::where("odoo_id", $request->payment_method)->first();
            if($payment_method){
                $payment_method_name = strtolower($payment_method->value);
            } else {
                $payment_method_name = "CASH";
            }
            $payment_reference = (new \App\Helpers\Helper)->getPaymentType($payment_method_name) . time();
            if(!empty($request->payment_reference)){
                $payment_reference =  (new \App\Helpers\Helper)->getPaymentType($payment_method_name) . $request->payment_reference;
            }
            $payment = Payment::create([
                'user_id' => $user->id,
                'cart_id' => $payment_reference,
                'status' => 'approved',
                'amount' => $totalAmount,
                'payment_type' => $payment_method_name,
                'lang' => $request->lang ?? "ar",
                'name_on_card' => $request->name_on_card,
                'bank_issuer' => $request->bank_issuer,
                "source" => $request->all()["x-source-type"] ?? null,
                "need_sync" => $request->all()["x-source-type"] ? 0 : 1,
                "odoo_id" => $request->paymentId
            ]);

            foreach ($cartItems as $cartItem) {
                $cartItem->update(['payment_id' => $payment->id]);
            }

            foreach ($subscriptionItems as $subscription) {
                $subscription->update(['payment_id' => $payment->id]);
            }

            PaymentUserDetail::create([
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $phone,
                'country' => $country ?? null
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Payment processed successfully',
                'payment' => $payment,
                'cart_items' => $cartItems,
                'subscriptions' => $subscriptionItems,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($request->all(), $e);
            return response()->json(['error' => 'Error processing payment', 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $this->logRequest($request->all());

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => ['required', 'string', new ForbiddenNameKeyword()],
            'last_name' => ['required', 'string', new ForbiddenNameKeyword()],
            'cart_items' => 'required|array',
            'cart_items.*.type' => 'required|string|in:one_time,monthly',
            'cart_items.*.price' => 'required|numeric',
            'cart_items.*.item_id' => 'required|integer',
            'cart_items.*.model' => 'required|string',
            'cart_items.*.quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error', 'message' => $validator->errors()], 400);
        }

        // Find the payment record by ID
        $payment = Payment::find($id);
        if (!$payment) {
            $this->logError($request->all(), 'Payment not found');
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Update user details (email, first_name, last_name)
        $user = $payment->user;

        $request->phone = str_replace(" ","", $request->phone);
        $user->update([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone ?? $user->phone,
            'country' => $request->country ?? $user->country,
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $cartItems = [];
            $subscriptionItems = [];

            // Process each cart item and update existing records
            foreach ($request->cart_items as $item) {
                $totalAmount += $item['price'] * $item['quantity'];

                $model = $this->getModelType($item['model']);

                $has_beneficiary = false;
                if ($model == "App\Models\Item") {
                    $data_item = Item::where("odoo_id", $item["item_id"])->first();
                    if ($data_item) {
                        $has_beneficiary = (bool) $data_item->has_beneficiary;
                    }
                }

                $option_id = null;
                $optionData = null;
                if(isset($item["dropdown_id"])){
                    $option_id = $item["dropdown_id"];

                    $priceOption = PriceOption::find($item["dropdown_id"]);
                    if ($priceOption) {
                        $optionData = json_encode([
                            'd1_option' => $priceOption->d1_option,
                            'd1_option_en' => $priceOption->d1_option_en,
                            'd2_option' => $priceOption->d2_option,
                            'd2_option_en' => $priceOption->d2_option_en,
                        ]);
                    }
                }

                // Check if the cart item exists and update it
                $cartItem = Cart::find($item['item_id']);
                if ($cartItem) {
                    $cartItem->update([
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'type' => $item['type'],
                        'model_type' => $model,
                        'is_paid' => 1,
                        'option_id' => $option_id,
                        'option_label' => $optionData
                    ]);
                } else {
                    // If not found, create a new one
                    $cartItem = Cart::create([
                        'user_id' => $user->id,
                        'item_id' => $item['item_id'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'type' => $item['type'],
                        'model_type' => $model,
                        'is_paid' => 1,
                        'option_id' => $option_id,
                        'option_label' => $optionData,
                        'has_beneficiary' => $has_beneficiary,
                    ]);
                }

                // Handle subscription if the item type is monthly
                if ($item['type'] === 'monthly') {
                    $subscription = Subscription::where('id', $cartItem->subscription_id)->first();
                    if ($subscription) {
                        $subscription->update([
                            'price' => $item['price'],
                            'start_date' => now(),
                            'end_date' => now()->addMonth(),
                        ]);
                    } else {
                        $subscription = Subscription::create([
                            'user_id' => $user->id,
                            'item_id' => $item['item_id'],
                            'subscription_id' => 'SUB' . time(),
                            'model_type' => $model,
                            'price' => $item['price'],
                            'start_date' => now(),
                            'end_date' => now()->addMonth(),
                            'status' => 'active',
                            'payment_id' => null,
                        ]);
                    }

                    $cartItem->update(['subscription_id' => $subscription->id]);
                    $subscriptionItems[] = $subscription;
                }

                $cartItems[] = $cartItem;
            }

            // Update the payment amount and other details
            $payment->update([
                'amount' => $totalAmount,
                'status' => 'approved',
                'payment_type' => $request->payment_method,
                'lang' => $request->lang,
            ]);

            foreach ($cartItems as $cartItem) {
                $cartItem->update(['payment_id' => $payment->id]);
            }

            foreach ($subscriptionItems as $subscription) {
                $subscription->update(['payment_id' => $payment->id]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment updated successfully',
                'payment' => $payment,
                'cart_items' => $cartItems,
                'subscriptions' => $subscriptionItems,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($request->all(), $e);
            return response()->json(['error' => 'Error updating payment', 'message' => $e->getMessage()], 500);
        }
    }


    private function getModelType(string $model): string
    {
        switch ($model) {
            case 'Item':
                return 'App\Models\Item';
            case 'QuickContribute':
                return 'App\Models\QuickContribution';
            default:
                throw new \InvalidArgumentException('Invalid model type');
        }
    }

    protected function logRequest($payload)
    {
        Log::channel('odoo')->info("ODDO API Payment ", [
            'payload'  => $payload
        ]);
    }

    protected function logError($payload, $error)
    {
        Log::channel('odoo')->error("Odoo API Payment ", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }
}
