<?php

namespace App\Http\Resources\Api\V1;

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
                "price" => (int) $item->price,
                "single_price" => ($item->price / $item->quantity),
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
            "cart_id" => $this->cart_id,
            "amount" => (int) $this->amount,
            "lang" => $this->lang,
            "cart_items" => $cart_items,
            'purchase_meta_pixel' => $this->purchase_meta_pixel,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
