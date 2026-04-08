<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryVideoResource extends JsonResource
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
            'video_url' => $this->video_url,
            'thumbnail' => $this->thumbnail,
            'category' => $this->category ? new NewsCategoryResource($this->category) : null,
        ];
    }
}
