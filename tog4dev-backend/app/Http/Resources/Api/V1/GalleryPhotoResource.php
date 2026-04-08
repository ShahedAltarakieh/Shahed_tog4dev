<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryPhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'description' => $this->getLocalizationDescription(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
            'image' => $this->image,
            'image_tablet' => $this->image_tablet,
            'image_mobile' => $this->image_mobile,
            'category' => $this->category ? new NewsCategoryResource($this->category) : null,
        ];
    }
}
