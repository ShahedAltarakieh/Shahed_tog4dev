<?php

namespace App\Http\Resources\Api\V2;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $default_price = ($this->amount != 0) ? $this->amount : null;
        $default_price_usd = ($this->amount_usd != 0) ? $this->amount_usd : null;
        $price_list = null;
        if($this->itemPrices){
            foreach ($this->itemPrices as $itemPrice) {
                $price_list[] = [
                    'id' => $itemPrice->id,
                    'price' => (int) $itemPrice->price,
                    'price_usd' => (int) $itemPrice->price_en
                ];
            }
        }

        $item_options = [];
        if($this->priceOptions){
            $item_options = $this->handlePrices($this->priceOptions);
        }

        $slider = $this->getMedia("items_gallery");
        $slider_images = [];
        foreach ($slider as $image){
            $slider_images[] = $image->getUrl();
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'location' => $this->location,
            'location_en' => $this->location_en,
            'slug' => $this->slug,
            'slug_en' => $this->slug_en,
            'image' => $this->image,
            'image_en' => $this->image_en,
            'price' => $default_price,
            'price_usd' => $default_price_usd,
            'price_list' => $price_list,
            'dropdown' => (count($item_options) > 0) ? $item_options : null,
            'payment_type' => $this->payment_type,
            "category_id" => $this->category_id,
            'type' => Helper::getFlipTypes($this->category->type),
            'additionalInfo' => $this->additionalInfo ? [
                "project_story" => $this->additionalInfo->project_story,
                "project_story_en" => $this->additionalInfo->project_story_en,
                "bold_description" => $this->additionalInfo->bold_description,
                "bold_description_en" => $this->additionalInfo->bold_description_en,
                "normal_description" => $this->additionalInfo->normal_description,
                "normal_description_en" => $this->additionalInfo->normal_description_en
            ] : null,
            "itemSlider" => (count($slider_images) > 0) ? $slider_images : null,
        ];
    }

    public function handlePrices($options)
    {
        $options_list = [];
        foreach ($options as $key => $value){
            $options_list[] = [
                "id" => $value->id,
                "title1" => $value->d1_option,
                "title1_en" => $value->d1_option_en,
                "title2" => $value->d2_option,
                "title2_en" => $value->d2_option_en,
                "price" => $value->price,
                "price_usd" => $value->price_en,
                "is_default" => $value->is_default
            ];
        }

        return $options_list;
    }
}
