<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
            'description' => $this->getLocalizationDescription(),
            'is_all_option' => (boolean) $this->all_option,
            'image' => $this->image,
            'hero_title' => $this->getLocalizationHeroTitle(),
            'hero_description' => $this->getLocalizationHeroDescription(),
            'hero_image' => $this->hero_image,
            'hero_image_tablet' => $this->hero_image_tablet ?? $this->hero_image,
            'hero_image_mobile' => $this->hero_image_mobile ?? $this->hero_image,
        ];
    }
}
