<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // Specify the table associated with the model
    protected $table = 'payments';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'cart_id',
        'status',
        'amount',
        'referrer_id',
        'collection_team_id',
        'contract_id',
        'temp_id',
        'payment_type',
        'acquirer_message',
        'subscription_id',
        'acquirer_rrn',
        'resp_code',
        'resp_message',
        'signature',
        'token',
        'tran_ref',
        'lang',
        'send_email',
        'odoo_column_to_payments',
        'response',
        'country',
        'cliq_number',
        'name_on_card',
        'bank_issuer',
        'not_send_email',
        'created_at',
        'updated_at',
        'odoo_id',
        'source',
        'need_sync',
        'purchase_meta_pixel',
        'retry_fetch_response',
        'efwateercom_payment_number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $setting = Setting::where('key', 'generate_email_payment_from_odoo')->first();
            if ($setting) {
                $payment->odoo_column_to_payments = $setting->value;
            }
        });
    }
    
    protected $casts = [
        'response' => 'array',
        'retry_fetch_response' => 'boolean',
    ];

    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'payment_id')->where('is_paid', 1);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'payment_id');
    }

    public function influencer()
    {
        return $this->belongsTo(Influencer::class, 'referrer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userDetails()
    {
        return $this->hasOne(PaymentUserDetail::class, 'payment_id');
    }

    public function toOdoo(): array
    {
        // Subscriptions
        $subscriptions = [];
        foreach ($this->subscriptions as $subscription) {
            $subscriptions[] = [
                'website_subscription_id' => $subscription->id ?? null,
                'subscription_item_id' => $subscription->item_id ?? null,
                'model_type' => ($subscription->model_type == "App\Models\QuickContribution") ? 'contribution' : 'item',
                'price' => (double) $subscription->price ?? null,
                'subscription_id' => $subscription->id ?? null,
                'status' => $subscription->status ?? null,
            ];
        }

        // Cart Items
        $cartItems = [];
        foreach ($this->cartItems as $cart) {
            $cartItems[] = [
                'id' => (string) $cart->id,
                'item_id' => $cart->item_id,
                "payment_type" => ($cart->type == "monthly") ? "subscription" : "one_time",
                'quantity' => $cart->quantity,
                'price' => (double) ($cart->price / $cart->quantity),
                'quick_contribute' => ($cart->model_type == "App\Models\QuickContribution") ? true : false,
                "analytic_id" => $cart->analyticـaccount_id,
                "dropdown_id" => $cart->option_id,
            ];
        }

        $payment_method = $this->getPaymentMethod($this->payment_type);

        if(!$payment_method){
            return [];
        }

        return [
            'params' => [
                "first_name" => $this->userDetails->first_name,
                "last_name" => $this->userDetails->last_name,
                "country" => $this->userDetails->country,
                "email" => $this->userDetails->email,
                "phone" => $this->userDetails->phone,
                'user_id' => $this->user_id,
                'amount' => (double) $this->amount,
                'id' => (string) $this->id,
                "payment_method" => (int) $payment_method,
                'status' => $this->status,
                'partner_bank_issuer' => $this->bank_issuer,
                'partner_card_holder' => $this->name_on_card,
                'collection_team_id' => $this->collection_team_id ?? 0,
                'referrer_id' => $this->referrer_id ?? 0,
                'contract' => $this->contract_id ?? 0,
                'lang' => strtolower($this->lang ?? 'ar'),
                'subscription_id' => $this->subscription_id ?? 0,
                'subscriptions' => $subscriptions,
                'payment_reference' => $this->cart_id,
                'cart_items' => $cartItems,
                "invoice_date" => date("Y-m-d", strtotime($this->created_at))
            ]
        ];
    }

    public static function getPaymentMethod($value)
    {
        return Setting::where('key', 'payment_method')->where('value', $value)->value('odoo_id');
    }

}
