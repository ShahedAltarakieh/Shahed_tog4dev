<?php

namespace App\Http\Resources\Api\V2;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cart_items = [];
        foreach ($this->cartItems as $item){
            $cart_items[] = [
                "id" => $item->id,
                "item_id" => $item->item_id,
                "model_type" => $item->model_type,
                "price" => (int) $item->price,
                "type" => $item->type,
                "quantity" => $item->quantity,
            ];
        }

        $subscriptions = [];
        foreach ($this->subscriptions as $item){
            $subscriptions[] = [
                "id" => $item->id,
                "item_id" => $item->item_id,
                "model_type" => $item->model_type,
                "price" => (int) $item->price,
                "subscription_id" => $item->subscription_id,
                "start_date" => $item->start_date,
                "end_date" => $item->end_date,
                "status" => $item->status
            ];
        }

        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "cart_id" => $this->cart_id,
            "status" => $this->status,
            "amount" => (int) $this->amount,
            "payment_type" => "Website - MEPS",
            "collection_team_id" => $this->collection_team_id,
            "referrer_id" => $this->referrer_id,
            "contract" => $this->contract_id,
            "lang" => $this->lang,
            "subscription_id" => $this->subscription_id,
            "cart_items" => $cart_items,
            "subscriptions" => (count($subscriptions) > 0) ? $subscriptions : null,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
