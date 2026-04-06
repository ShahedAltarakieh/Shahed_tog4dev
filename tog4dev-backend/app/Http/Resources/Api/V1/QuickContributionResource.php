<?php

namespace App\Http\Resources\Api\V1;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class QuickContributionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price_list = null;
        if($this->prices){
            foreach ($this->prices as $itemPrice) {
                $price_list[] = (int) $itemPrice->price;
            }
        }

        $percentage_amount = null;
        $total_paid = null;
        if($this->cartItemsPaid){
            foreach ($this->cartItemsPaid as $item){
                $percentage_amount += $item->price;
            }
            $total_paid = $percentage_amount;
            if($this->target && $this->target > 0){
                $percentage_amount = ($percentage_amount / $this->target) * 100;
                $percentage_amount = (double) number_format($percentage_amount, 2, '.', '');
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'description' => $this->getLocalizationDescription(),
            'image' => $this->getLocalizationImage(),
            'image_tablet' => $this->image_tablet ?? $this->getLocalizationImage(),
            'image_mobile' => $this->image_mobile ?? $this->getLocalizationImage(),
            'target' => $this->target,
            'single_price' => $this->single_price,
            'price_list' => $price_list,
            'total_paid' => $total_paid,
            'percentage_amount' => $percentage_amount,
        ];
    }
}
