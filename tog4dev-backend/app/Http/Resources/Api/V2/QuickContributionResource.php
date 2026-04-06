<?php

namespace App\Http\Resources\Api\V2;

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
        $price_list_en = null;
        if($this->prices){
            foreach ($this->prices as $itemPrice) {
                $price_list[] = [
                    'id' => $itemPrice->id,
                    'price' => (int) $itemPrice->price,
                    'price_usd' => (int) $itemPrice->price_usd
                ];
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'location' => $this->location,
            'location_en' => $this->location_en,
            'image' => $this->image,
            'image_en' => $this->image_en,
            'target' => $this->target,
            'target_usd' => $this->target_usd,
            'type_id' => $this->type_id,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'price_list' => $price_list,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null
        ];
    }
}
