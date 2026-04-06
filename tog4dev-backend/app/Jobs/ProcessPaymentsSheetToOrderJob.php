<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Helpers\PhoneHelper;
use App\Models\ExcelOrders;
use App\Models\User;
use App\Models\PriceOption;
use App\Models\Payment;
use App\Models\PaymentUserDetail;
use App\Models\Cart;
use App\Models\Subscription;
use App\Models\MappingZbooniItem;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessPaymentsSheetToOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sheet_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sheet_id)
    {
        $this->sheet_id = $sheet_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = ExcelOrders::where("excel_sheet_id", $this->sheet_id)
        ->where('status', 'pending')  // Order status is 'pending'
        ->whereNotNull('order_id')
        ->where('order_id',"<>", '')->get();
        if ($orders->isEmpty()) {
            return;
        }

        foreach($orders as $order){
            $order_id = (new \App\Helpers\Helper)->getPaymentType($order["payment_method"]).$order['order_id'];
            $check_if_exist = Payment::where("cart_id", $order_id)->count();
            if($check_if_exist > 0){
                continue;
            }
            if(empty($order->customer_email)){
                continue;
            }
            $user = User::where('email', trim($order->customer_email))->first();
            if(!$user){
                $user = $this->createUser($order);
            }
            $inserted = $this->createOrder($order, $user);
            if($inserted){
                // Update the status of the order to 'completed' after user creation
                $order->status = 'completed';
                $order->save();
            } else {
                continue;
            }
        }
    }

    public function createOrder($order, $user){
        try {
        DB::beginTransaction(); // Start the transaction
        // Split by comma (without trimming spaces)
        $array = explode('#', $order["order_items"]);
        foreach($array as $k => $v){
            $v = trim($v);
            if(empty($v)){
                unset($array[$k]);
            }
        }

        // Trim each item to remove any extra spaces
        $array = array_map('trim', $array);
        $items_ids = [];
        foreach($array as $key => $item){
            $item_details = explode('-', $item);
            $item_details = array_map('trim', $item_details);
            if(count($item_details) == 5){
                // $item_details[1] = preg_replace('/\s*\d+(\.\d+)?\s*$/u', '', $item_details[1]);
                $item = MappingZbooniItem::where('model_type', $item_details[0])->where('zbooni_name', $item_details[1])->first();
                if($item){
                    if($item_details[0] == "كويك" || $item_details[0] == "كويك شهري"){
                        $items_ids[$key] = [
                            "item_data" => $item->quickContribution,
                            "item_excel" => $item_details
                        ];
                    } else {
                        $items_ids[$key] = [
                            "item_data" => $item->item,
                            "item_excel" => $item_details
                        ];
                    }
                }
            }
        }

        $total = str_replace('JOD', '', $order["total"]);
        $dont_insert = false;
        $cart = [];
        $subscription = 0;
        if(count($items_ids) > 0){
            foreach ($items_ids as $key => $value) {
                $payment_type = "one_time";
                $item_our_db = $value["item_data"];
                $item_excel = $value["item_excel"];
                $total_cart = $item_excel[4];
                if(($item_excel[0] == "كويك" || $item_excel[0] == "كويك شهري") && $item_our_db->category->type == 2){
                    if($item_excel[0] == "كويك شهري"){
                        $subscription = 1;
                        $payment_type = "monthly";
                    }
                    $quantity = 1;
                    $cart[] = [
                        "user_id" => $user->id,
                        "item_id" => $item_our_db["id"],
                        "model_type" => "App\Models\QuickContribution",
                        "payment_id" => null,
                        "price" => $item_excel[4],
                        "quantity" => $quantity,
                        "collection_team_id" => null,
                        "type" => $payment_type,
                        "is_paid" => 1,
                        "created_at" => $order["created_order_at"],
                        "updated_at" => $order["created_order_at"],
                        "title" => $item_our_db["title"],
                        "title_en" => $item_our_db["title_en"],
                        "description" => $item_our_db["description"],
                        "description_en" => $item_our_db["description_en"],
                        "location" => $item_our_db["location"],
                        "location_en" => $item_our_db["location_en"],
                        "option_id" => null,
                        "option_label" => null,
                        "analyticـaccount_id" => $item_our_db["analyticـaccount"],
                    ];
                }
                else if($item_excel[0] == "كراود" && $item_our_db->category->type == 3){
                    $quantity = 1;
                    $cart[] = [
                        "user_id" => $user->id,
                        "item_id" => $item_our_db["id"],
                        "model_type" => "App\Models\Item",
                        "payment_id" => null,
                        "price" => $item_excel[4],
                        "quantity" => $quantity,
                        "collection_team_id" => null,
                        "type" => "one_time",
                        "is_paid" => 1,
                        "created_at" => $order["created_order_at"],
                        "updated_at" => $order["created_order_at"],
                        "title" => $item_our_db["title"],
                        "title_en" => $item_our_db["title_en"],
                        "description" => $item_our_db["description"],
                        "description_en" => $item_our_db["description_en"],
                        "location" => $item_our_db["location"],
                        "location_en" => $item_our_db["location_en"],
                        "option_id" => null,
                        "option_label" => null,
                        "analyticـaccount_id" => $item_our_db["analyticـaccount"],
                    ];
                } else if($item_excel[0] == "مشروع" && $item_our_db->category->type == 2){
                    $single_price = $item_excel[4] / $item_excel[3];
                    $options = PriceOption::where("item_id", $item_our_db["id"])->get();
                    // if($total_cart != ($item_our_db["amount"] * $item_excel[3])){
                    //     $dont_insert = true;
                    //     $this->error("issue in amount for Order ID " . $order->order_id . ": " . $e->getMessage());
                    // }

                    $option_dropdown = null;
                    if($options->count() > 0){
                        foreach($options as $k => $option){
                            if($option["price"] == $single_price){
                                $dont_insert = false;
                                $option_dropdown = $option;
                                break;
                            }
                        }
                    }

                    $cart[] = [
                        "user_id" => $user->id,
                        "item_id" => $item_our_db["id"],
                        "model_type" => "App\Models\Item",
                        "payment_id" => null,
                        "price" => $item_excel[4],
                        "quantity" => $item_excel[3],
                        "collection_team_id" => null,
                        "type" => "one_time",
                        "is_paid" => 1,
                        "created_at" => $order["created_order_at"],
                        "updated_at" => $order["created_order_at"],
                        "title" => $item_our_db["title"],
                        "title_en" => $item_our_db["title_en"],
                        "description" => $item_our_db["description"],
                        "description_en" => $item_our_db["description_en"],
                        "location" => $item_our_db["location"],
                        "location_en" => $item_our_db["location_en"],
                        "option_id" => ($option_dropdown) ? $option_dropdown->id : null,
                        "option_label" => ($option_dropdown) ? json_encode(['d1_option' => $option_dropdown->d1_option, 'd1_option_en' => $option_dropdown->d1_option_en, 'd2_option' => $option_dropdown->d2_option, 'd2_option_en' => $option_dropdown->d2_option_en ]) : null,
                        "analyticـaccount_id" => $item_our_db["analyticـaccount"],
                    ];
                }
            }
        }

        if($dont_insert){
            $cart = [];
        }
        if(count($cart) != count($array)){
            $cart = [];
        }
        
        if(count($cart) > 0){
            $payment = Payment::create([
                'user_id' => $user->id,
                'cart_id' => (new \App\Helpers\Helper)->getPaymentType($order["payment_method"]).$order['order_id'],
                'status' => 'approved',
                'amount' => trim($total),
                'payment_type' => $order["payment_method"],
                'referrer_id' => $order["inf_id"],
                'acquirer_message' => null,
                'acquirer_rrn' => null,
                'resp_code' => null,
                'resp_message' => null,
                'signature' => null,
                'token' => null,
                'tran_ref' => $order['order_id'],
                'lang' => $order["lang"],
                'send_email' => 0,
                "subscription_id" => null,
                "not_send_email" => 0,
                "created_at" => $order["created_order_at"],
                "updated_at" => $order["created_order_at"]
            ]);

            foreach($cart as &$single_cart){
                $single_cart["payment_id"] = $payment->id;
                if($single_cart["type"] == "monthly"){
                    Subscription::create([
                        'user_id' => $user->id,
                        'item_id' => $single_cart["item_id"],
                        'subscription_id' => 'SUB' . time(),
                        'model_type' => $single_cart["model_type"],
                        'price' => $single_cart["price"],
                        'start_date' => $order["created_order_at"],
                        'end_date' => Carbon::parse($order["created_order_at"])->addMonth(),
                        'temp_id' => null,
                        'status' => 'active',
                        'payment_id' => $payment->id,
                        "title" => $single_cart["title"],
                        "title_en" => $single_cart["title_en"],
                        "description" => $single_cart["description"],
                        "description_en" => $single_cart["description_en"],
                        "location" => $single_cart["location"],
                        "location_en" => $single_cart["location_en"],
                    ]);
                }
            }
            Cart::insert($cart);

            $name_list = $this->getFormattedName($order["name"]);

            // Set first_name and last_name
            $firstName = $name_list["first_name"];
            $lastName = $name_list["last_name"];

            $phone_list = PhoneHelper::getPhoneDetails($order["customer_phone_number"]);

            $phoneNumber = $phone_list["phone"];
            $countryCode = $phone_list["countryCode"];
            $country = $phone_list["country"];

            $payment_details = PaymentUserDetail::create([
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $order["customer_email"],
                "phone" => $phoneNumber,
                "country" => $country,
            ]);

            // Commit the transaction if everything went fine
            DB::commit();

            return true;
        } else {
            // $this->error("Error creating order ID " . $order["order_id"] . ": " . $e->getMessage());
        }

        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();
            // $this->error("Error creating order ID " . $order["order_id"] . ": " . $e->getMessage());
            // Optionally, you can log the error to a log file for debugging
            \Log::error('Error creating order: ' . $e->getMessage());
        }
    }

    public function createUser($order){
        DB::beginTransaction(); // Start the transaction

        try {
            $name_list = $this->getFormattedName($order->name);
            
            // Set first_name and last_name
            $firstName = $name_list["first_name"];
            $lastName = $name_list["last_name"];

            $phone_list = PhoneHelper::getPhoneDetails($order->customer_phone_number);

            $phoneNumber = $phone_list["phone"];
            $countryCode = $phone_list["countryCode"];
            $country = $phone_list["country"];

            // Create a new User based on the order details
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,    
                'email' => $order->customer_email,
                'phone' => $phoneNumber,  // Add phone_number field in User model if it doesn't exist
                'role' => 2,
                'country' => $country,
                'created_from' => 'excel',
                'password' => bcrypt('defaultPassword'),  // You can set a default password or generate one
            ]);

            // Commit the transaction if everything went fine
            DB::commit();

            // $this->info("User created successfully: " . $user->first_name . ' ' . $user->last_name);

            // Return the created user
            return $user;

        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();

            // $this->error("Error creating user for Order ID " . $order->order_id . ": " . $e->getMessage());
            // Optionally, you can log the error to a log file for debugging
            \Log::error('Error creating user: ' . $e->getMessage());
        }
    }


    private function getFormattedName($name){
        $name = str_replace(["أ.", "المحترمة", "المحترم", "الفاضلة"], "", $name);
        $name = trim($name);
        // Split the name into first and last names
        $nameParts = explode(' ', $name, 2); // Split only into two parts (first and last)
        $nameParts = array_map('trim', $nameParts);
        // Set first_name and last_name
        $firstName = trim($nameParts[0]) ?? null; // The first part is the first name
        $lastName = (isset($nameParts[1]) && trim($nameParts[1])) ? $nameParts[1] : null;  // The second part is the last name (if available)

        return [
            "first_name" => $firstName,
            "last_name" => $lastName
        ];
    }
}
