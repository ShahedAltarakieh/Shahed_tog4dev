<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->getLocalizationName(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
        ];
    }
}
