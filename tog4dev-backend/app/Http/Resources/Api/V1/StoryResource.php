<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'image' => $this->getLocalizationImage(),
            'image_tablet' => $this->image_tablet ?? $this->getLocalizationImage(),
            'image_mobile' => $this->image_mobile ?? $this->getLocalizationImage(),
            'category' => [
                "id" => $this->category->id,
                "title" => $this->category->getLocalizationTitle(),
                'slug' => $this->category->getLocalizationSlug(),
                'slug_ar' => $this->category->slug,
                'slug_en' => $this->category->slug_en,
                'type' => $this->category->type,
            ]
        ];
    }
}
