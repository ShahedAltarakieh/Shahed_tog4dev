<?php 

namespace App\Services;

use App\Models\Payment;
use App\Models\Cart;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Paytabscom\Laravel_paytabs\Facades\paypage;

class PaytabsPaymentService implements PaymentGatewayInterface
{
    public function handlePayment(Request $request)
    {
        return $this->processPaytabsPayment($request);
    }

    public function processPaytabsPayment(Request $request)
    {
        $temp_id = $request->get('temp_id') ?? '';
        $locale = app()->getLocale();
        $user = auth('sanctum')->user();

        if (!$user) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        $cartItems = Cart::where('user_id', $user->id)->where('is_paid', false)->get();
        $cartAmount = 0;
        $descPaymentMeps = '';
        $checkSubscription = 0;

        foreach ($cartItems as $key => $cartItem) {
            $cartAmount += $cartItem->price;
            $descPaymentMeps .= ($key + 1) . "-" . $cartItem->item->getLocalizationTitle() . " (" . $cartItem->type . ") ";

            if ($cartItem->type == 'monthly') {
                $checkSubscription = 1;
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

        $pay = paypage::sendPaymentCode('all')
            ->sendTransaction('sale', 'ecom')
            ->sendCart('CART' . time(), $cartAmount, $descPaymentMeps)
            ->sendCustomerDetails($user->first_name, $user->email)
            ->create_pay_page();

        return response()->json($pay, 200);
    }
}
