<?php

namespace App\Http\Controllers;

use App\Helpers\PhoneHelper;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Exports\PaymentsCliqExport;
use App\Exports\CategoriesExport;
use App\Exports\QuickContributionExport;
use App\Exports\PriceListExport;
use App\Exports\PriceOptionExport;
use App\Exports\ItemsExport;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PaymentUserDetail;
use App\Models\DeletedPayment;
use App\Models\Cart;
use App\Models\Item;
use App\Models\QuickContribution;
use App\Models\Subscription;
use App\Mail\AdhaCampaignMail;
use Illuminate\Support\Facades\Mail;
use App\Services\NetworkPaymentService;
use Illuminate\Support\Facades\DB;
use App\Mail\SendUnsubscriptionMail2;

class CommonController extends Controller
{
    protected $networkPaymentService;

    public function __construct(NetworkPaymentService $networkPaymentService)
    {
        $this->networkPaymentService = $networkPaymentService;
    }

    public function fix_country()
    {
        $this->fix_country_two_char();
    }

    private function fix_country_two_char()
    {
        $users = User::whereRaw('LENGTH(country) = 2')->get();
        foreach ($users as $user){
            $countryName = PhoneHelper::getCountry($user->country);
            if($countryName){
                $user->update(["country" => $countryName]);
            }
        }
    }

    public function fix_phone()
    {
        $this->fix_arabic_phone_number();
        $this->remove_space_symbol();
        $this->fix_phone_by_country();
        $this->replace_zero_to_plus();
        $this->replace_96207_to_9627();
        $this->replace_96605_to_9625();
    }

    private function fix_arabic_phone_number()
    {
        $users = User::all();
        $users->each(function ($user) {
            $user->phone = str_replace(
                ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
                ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
                $user->phone
            );
            $user->save();
        });
    }

    public function fix_phone_by_country()
    {
        $users = User::where("country", "jordan")->get();

        // Path to the JSON file in the public folder
        $filePath = public_path('countries.json');
        $countriesList = json_decode(file_get_contents($filePath), true);

        $users->each(function ($user) use ($countriesList) {
            $country = collect($countriesList)->firstWhere('country_name_english', $user->country);

            $phone = preg_replace('/\D/', '', $user->phone); // Remove non-numeric characters
            $phone = $user->phone;
            $flag_update = true;
            // Ensure Jordanian format
            if (str_starts_with($phone, '00962')) {
                $phone = '+962' . substr($phone, 5); // Remove '00962' and add '+962'
            } elseif (str_starts_with($phone, '962')) {
                $phone = '+962' . substr($phone, 3); // Remove '962' and add '+962'
            } elseif (str_starts_with($phone, '07')) {
                $phone = '+962' . substr($phone, 1); // Remove leading '0' and add '+962'
            } elseif (str_starts_with($phone, '7')) {
                $phone = '+962' . $phone; // Prepend '+962' to a 9-digit number
            }
            else{
                $flag_update = false;
            }

            // Update the phone number if changed
            if ($flag_update) {
                $user->phone = $phone;
                $user->save();
            }
        });
    }

    public function remove_space_symbol()
    {
        $users = User::all();

        $users->each(function ($user) {
            $cleanedPhone = preg_replace('/[()\-\_\s]+/', '', $user->phone);
            $cleanedPhone = str_replace(' ', '', $cleanedPhone); // Remove spaces
            // Update only if the phone number has changed
            if ($user->phone !== $cleanedPhone) {
                $user->phone = $cleanedPhone;
                $user->save();
            }
        });
    }

    private function replace_zero_to_plus()
    {
        $users = User::all();

        $users->each(function ($user) {
            $phone = $user->phone;

            // Check if phone starts with "00" and replace it with "+"
            if (str_starts_with($phone, '00')) {
                $phone = '+' . substr($phone, 2); // Remove "00" and add "+"
            }

            // Update only if the phone number has changed
            if ($user->phone !== $phone) {
                $user->phone = $phone;
                $user->save();
            }
        });
    }

    public function replace_96207_to_9627()
    {
        $users = User::where('phone', 'like', '+9620%')->get();
        $users->each(function ($user) {
            $phone = $user->phone;

            $phone = str_replace('+9620', '+962', $user->phone); // Remove spaces

            // Update only if the phone number has changed
            if ($user->phone !== $phone) {
                $user->phone = $phone;
                $user->save();
            }
        });
    }

    public function replace_96605_to_9625()
    {
        $users = User::where('phone', 'like', '+9660%')->get();
        $users->each(function ($user) {
            $phone = $user->phone;

            $phone = str_replace('+9660', '+966', $user->phone); // Remove spaces

            // Update only if the phone number has changed
            if ($user->phone !== $phone) {
                $user->phone = $phone;
                $user->save();
            }
        });
    }


    public function download_excel_cliq(){
        return Excel::download(new PaymentsCliqExport, 'payments.xlsx');        
    }

    public function download_excel_categories(){
        return Excel::download(new CategoriesExport, 'categories.xlsx');        
    }

    public function download_excel_quicks(){
        return Excel::download(new QuickContributionExport, 'quick-contribute.xlsx');        
    }

    public function download_excel_items(){
        return Excel::download(new ItemsExport, 'items.xlsx');        
    }

    public function download_excel_price_list(){
        if(isset($_GET["quick"])){
            return Excel::download(new PriceListExport("quick"), 'price list quick.xlsx');
        } else {
            return Excel::download(new PriceListExport(), 'price list items.xlsx');
        }
    }

    public function download_excel_price_options(){
        return Excel::download(new PriceOptionExport, 'price options.xlsx');        
    }
    

    public function fix_old_payments_name(){
        $payments = Payment::where('status', 'approved')->with(['user'])->get();
        foreach($payments as $payment){
            if(!$payment->userDetails){
                $country = $payment->user->country ?? null;
                $phone = $payment->user->phone ?? null;
                if($country == null && $phone != null){
                    $phone_details = PhoneHelper::getPhoneDetails($payment->user->phone);

                    if(isset($phone_details["country"]) && !empty($phone_details["country"])){
                        $country = $phone_details["country"];
                    }
                }
                $payment_details = PaymentUserDetail::create([
                    'user_id' => $payment->user->id,
                    'payment_id' => $payment->id,
                    'first_name' => $payment->user->first_name,
                    'last_name' => $payment->user->last_name,
                    'email' => $payment->user->email,
                    "phone" => $payment->user->phone,
                    "country" => $country,
                ]);
            }
        }
    }

    public function storeItemDetailsFromCartToCartDetails(){
        Cart::with(['quickContribute', 'item'])
            ->chunk(1000, function ($items) {
                foreach ($items as $item) {
                    if($item->model_type == "App\Models\QuickContribution"){
                        if($item->quickContribute){
                            if(empty($item->title)){
                                $item->title = $item->quickContribute->title;
                            }
                            if(empty($item->title_en)){
                                $item->title_en = $item->quickContribute->title_en;
                            }
                            if(empty($item->description)){
                                $item->description = $item->quickContribute->description;
                            }
                            if(empty($item->description_en)){
                                $item->description_en = $item->quickContribute->description_en;
                            }
                            if(empty($item->location)){
                                $item->location = $item->quickContribute->location;
                            }
                            if(empty($item->location_en)){
                                $item->location_en = $item->quickContribute->location_en;
                            }
                            $item->update();
                        }
                    } else{
                        if($item->item){
                            if(empty($item->title)){
                                $item->title = $item->item->title;
                            }
                            if(empty($item->title_en)){
                                $item->title_en = $item->item->title_en;
                            }
                            if(empty($item->description)){
                                $item->description = $item->item->description;
                            }
                            if(empty($item->description_en)){
                                $item->description_en = $item->item->description_en;
                            }
                            if(empty($item->location)){
                                $item->location = $item->item->location;
                            }
                            if(empty($item->location_en)){
                                $item->location_en = $item->item->location_en;
                            }
                            $item->update();
                        }
                    }
                }
        });

        Subscription::with(['quickContribute', 'item'])
            ->chunk(1000, function ($items) {
                foreach ($items as $item) {
                    if($item->model_type == "App\Models\QuickContribution"){
                        if($item->quickContribute){
                            if(empty($item->title)){
                                $item->title = $item->quickContribute->title;
                            }
                            if(empty($item->title_en)){
                                $item->title_en = $item->quickContribute->title_en;
                            }
                            if(empty($item->description)){
                                $item->description = $item->quickContribute->description;
                            }
                            if(empty($item->description_en)){
                                $item->description_en = $item->quickContribute->description_en;
                            }
                            if(empty($item->location)){
                                $item->location = $item->quickContribute->location;
                            }
                            if(empty($item->location_en)){
                                $item->location_en = $item->quickContribute->location_en;
                            }
                            $item->update();
                        }
                    } else{
                        if($item->item){
                            if(empty($item->title)){
                                $item->title = $item->item->title;
                            }
                            if(empty($item->title_en)){
                                $item->title_en = $item->item->title_en;
                            }
                            if(empty($item->description)){
                                $item->description = $item->item->description;
                            }
                            if(empty($item->description_en)){
                                $item->description_en = $item->item->description_en;
                            }
                            if(empty($item->location)){
                                $item->location = $item->item->location;
                            }
                            if(empty($item->location_en)){
                                $item->location_en = $item->item->location_en;
                            }
                            $item->update();
                        }
                    }
                }
        });
    }

    public function send_email(){
         Mail::mailer('info')->to("fatoh.abualrub@gmail.com")->send(new AdhaCampaignMail());
    }

    public function store_card_on_name(){
        $payments = Payment::where('status', 'approved')->where('payment_type', 'network')->get();
        foreach($payments as $payment){
            if(!empty($payment->response)){
                $response = $payment->response;
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
                        $payment->save();
                    }
                }
            }
        }
    }

    public function fetch_network_info(){
        $payments = Payment::where('status', 'approved')->where('payment_type', 'network')->whereNull('response')->get();
        foreach($payments as $payment){
            if(empty($payment->response)){
                $response = $this->networkPaymentService->getOrderDetails($payment->cart_id);
                $payment->response = $response;
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
                        $payment->save();
                    }
                }
            }
        }
    }

    public function update_ana_names(){
        

        $carts = Cart::whereNull('analyticـaccount_id')->get();
        foreach($carts as $k => $v){
            if($v->model_type == "App\Models\QuickContribution"){
                $analytic_id = $v->quickContribute->analyticـaccount;
            } else {
                $analytic_id = $v->item->analyticـaccount;
            }
            $v->analyticـaccount_id = $analytic_id;
            $v->save();
        }
    }

    public function fix_deleted_orders(){
        $filePath = public_path('payment-deleted.xlsx');
        $rows = Excel::toArray([], $filePath)[0];
        $header = array_map('strtolower', $rows[0]);
        $orderIdIndex = array_search('order id', $header);
        $orderIdIndex2 = array_search('payment method', $header);
        unset($rows[0]);
        $array = [];
        foreach ($rows as $row) {
            $orderId = trim($row[$orderIdIndex] ?? '');
            if (!$orderId) continue;
            $order_id = (new \App\Helpers\Helper)->getPaymentType($row[$orderIdIndex2]).$orderId;
            $payment = Payment::where("cart_id", $order_id)->first();
            if(!$payment){
                $array[] = $row;
                continue;
            } else {
                DB::transaction(function () use ($payment) {
                    // Insert into deleted_payments
                    DeletedPayment::create($payment->toArray());
                    // Delete from payments
                    $payment->delete();
                });
            }
        }
        dd($array);
    }

    public function updateCartDuplicate(){

        $payments = Payment::withSum('cartItems', 'price')->where('status', 'approved')->skip(0)->take(3000)->get();

        $paymentsWithMismatch = $payments->filter(function ($payment) {
            return $payment->amount != $payment->cart_items_sum_price;
        });
        foreach($paymentsWithMismatch as $k => $v){
            if($v->cartItems->count() == 1){
                echo $v->id." ---- ".$v->contract_id." =======".$v->amount." == ".$v->cart_items_sum_price." == ".$v->cartItems[0]->quantity."<br>";
            } else {
                dd($v->cartItems);
            }
            if($v->cartItems->count() == 1){
                foreach ($v->cartItems as $key => $value) {
                    $value->price = ($value->price * $value->quantity);
                    $value->save();
                }
            }
        }
        dd($paymentsWithMismatch);
        // foreach($paymentsWithMismatch as $k => $v){
        //     if($v->cartItems->count() == 1){
        //         // dd($v);
        //         foreach ($v->cartItems as $key => $value) {
        //             $value->price = ($value->price * $value->quantity);
        //             $value->save();
        //         }
        //         // $v->amount = $v->cartItems->sum("price");
        //         // $v->save();
        //     }
        // }

        // $carts = DB::table('cart as c')
        //     ->leftJoin('payments as p', 'c.payment_id', '=', 'p.id')
        //     ->select(
        //         'c.id',
        //         'c.price',
        //         'c.payment_id',
        //         'c.quantity',
        //         'p.id as payment_id',
        //         'p.payment_type',
        //         'p.cart_id',
        //         'c.created_at as cart_created_at',
        //         'p.created_at as payment_created_at',
        //         'p.amount as payment_amount',
        //         'p.contract_id as contract_id'
        //     )
        //     ->whereNotNull('c.payment_id')
        //     ->where('c.quantity', '>', 1)
        //     ->where('c.is_paid', 1)
        //     ->whereNotIn('p.payment_type', ['meps', 'Network'])
        //     ->get();

        //     $a = 0;
        //     echo "<pre>";
        // foreach($carts as $k => $v){
        //     $payment = Payment::find($v->payment_id);
        //     if($payment->cartItems->count() > 1){
        //         // foreach($payment->cartItems as $kk => $vv){
        //         //     if($vv->price != $vv->payment_amount){
        //         //         $vv->price = $vv->quantity * $vv->price;
        //         //         $vv->save();
        //         //     }
        //         //     die;
        //         // }
        //     } else{
        //         if($v->price != $v->payment_amount){
        //             $cart = Cart::find($v->id);
        //             $cart->price = $v->quantity * $v->price;
        //             $cart->save();
        //         }
        //     }
        // }
    }

    public function send_test_unsubscribtion(){
        $quicks = QuickContribution::all();
        $user = User::where("email", "Malak.alashi97@gmail.com")->first();
        // $user = User::where("email", "fatoh.abualrub@gmail.com")->first();
        foreach($quicks as $k => $v){
            Mail::to("Malak.alashi97@gmail.com")->cc("fatoh.abualrub@gmail.com")->send(new SendUnsubscriptionMail2($v, $user, "en"));
            Mail::to("Malak.alashi97@gmail.com")->cc("fatoh.abualrub@gmail.com")->send(new SendUnsubscriptionMail2($v, $user, "ar"));
        }
    }
}
