<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
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
            'name' => $this->getLocalizationName(),
            'description' => $this->getLocalizationDescription(),
            'image' => $this->getLocalizationImage(),
            'image_tablet' => $this->image_tablet ?? $this->getLocalizationImage(),
            'image_mobile' => $this->image_mobile ?? $this->getLocalizationImage(),
            'location' => $this->getLocalizationLocation()
        ];
    }
}
