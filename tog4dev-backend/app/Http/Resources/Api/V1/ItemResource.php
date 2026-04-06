<?php

namespace App\Http\Resources\Api\V1;

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
        $price_list = null;
        if($this->itemPrices){
            foreach ($this->itemPrices as $itemPrice) {
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
            $percentage_amount = ($percentage_amount / $this->amount) * 100;
            $percentage_amount = (double) number_format($percentage_amount, 2, '.', '');
            if($this->id == 94){
                $percentage_amount += 7;
            }
        }
        $item_options = null;
        if($this->priceOptions){
            $item_options = $this->handlePrices($this->priceOptions);
            if(isset($item_options["default_price"]) && !empty($item_options["default_price"])){
                $default_price = $item_options["default_price"];
            }
            unset($item_options["default_price"]);
        }

        $slider = $this->getMedia(collectionName: "items_gallery");
        $slider_images = [];
        foreach ($slider as $image){
            $slider_images[] = $image->getUrl();
        }
        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'description' => $this->getLocalizationDescription(),
            'location' => $this->getLocalizationLocation(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'total_paid' => $total_paid,
            'slug_en' => $this->slug_en,
            'price' => $default_price,
            'single_price' => $this->single_price,
            'percentage_amount' => $percentage_amount,
            'price_list' => $price_list,
            'dropdown' => $item_options,
            'image' => $this->getLocalizationImage(),
            'image_tablet' => $this->image_tablet ?? $this->getLocalizationImage(),
            'image_mobile' => $this->image_mobile ?? $this->getLocalizationImage(),
            'payment_type' => $this->payment_type,
            'type_id' => $this->category->type,
            'type' => Helper::getFlipTypes($this->category->type),
            'additionalInfo' => $this->additionalInfo ? [
                "project_story" => $this->additionalInfo->getLocalizationProjectStory(),
                "bold_description" => $this->additionalInfo->getLocalizationBoldDescription(),
                "normal_description" => $this->additionalInfo->getLocalizationNormalDescription()
            ] : null, // Handle the case where additionalInfo is null
            "itemSlider" => $slider_images,
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->getLocalizationTitle(),
                'slug' => $this->category->getLocalizationSlug(),
                'slug_ar' => $this->category->slug,
                'slug_en' => $this->category->slug_en,
                'description' => $this->category->getLocalizationDescription(),
                'is_all_option' => (boolean) $this->category->all_option,
                'image' => $this->category->getLocalizationImage(),
                'hero_title' => $this->category->getLocalizationHeroTitle(),
                'hero_description' => $this->category->getLocalizationHeroDescription(),
                'hero_image' => $this->category->getLocalizationHeroImage()
            ]
        ];
    }

    public function handlePrices($options)
    {
        $options_list = [];
        $default_price = 0;
        foreach ($options as $key => $value){
            if($value->is_default == 1){
                $default_price = $value->price;
            }
            $options_list[] = [
                "id" => $value->id,
                "title_d1" => $value->getLocalizationD1Option(),
                "title_d2" => $value->getLocalizationD2Option(),
                "price" => $value->price,
                "is_default" => $value->is_default
            ];
        }

        return [
            "options" => $options_list,
            "default_price" => $default_price
        ];
    }
}
