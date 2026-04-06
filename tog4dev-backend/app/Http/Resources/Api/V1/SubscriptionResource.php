<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->model_type == "App\Models\QuickContribution"){
            $item = [
                "title" => $this->quickContribute->getLocalizationTitle(),
                "description" => $this->quickContribute->getLocalizationDescription(),
                "image" => $this->quickContribute->getLocalizationImage(),
                "location" => $this->quickContribute->getLocalizationLocation(),
                "category_name" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationTitle() : null,
                "category_image" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationImage() : null,
                "category_slug" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationSlug() : null,
                "category_slug_ar" => ($this->quickContribute->category) ? $this->quickContribute->category->slug : null,
                "category_slug_en" => ($this->quickContribute->category) ? $this->quickContribute->category->slug_en : null,
                "category_type" => ($this->quickContribute->category) ? $this->quickContribute->category->type : null,
            ];
        } else {
            $item = [
                "title" => $this->item->getLocalizationTitle(),
                "description" => $this->item->getLocalizationDescription(),
                "image" => $this->item->getLocalizationImage(),
                "location" => $this->item->getLocalizationLocation(),
                "category_name" => $this->item->category->getLocalizationTitle(),
                "category_image" => $this->item->category->getLocalizationImage(),
                "category_slug" => $this->item->category->getLocalizationSlug(),
                "category_slug_ar" => $this->item->category->slug,
                "category_slug_en" => $this->item->category->slug_en,
                "category_type" => $this->item->category->type,
            ];
        }
        return [
            'id' => $this->id,
            'subscription_id' => $this->subscription_id,
            'status' => $this->status,
            'amount' => (int) $this->price,
            'next_payment' => date('Y/m/d', strtotime($this->end_date)),
            'item' => $item,
        ];
    }
}
