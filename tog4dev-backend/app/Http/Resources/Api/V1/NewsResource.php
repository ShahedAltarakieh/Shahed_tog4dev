<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
            'excerpt' => $this->getLocalizationExcerpt(),
            'body' => $this->getLocalizationBody(),
            'image' => $this->image,
            'image_tablet' => $this->image_tablet,
            'image_mobile' => $this->image_mobile,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at ? $this->published_at->toIso8601String() : null,
            'category' => $this->category ? new NewsCategoryResource($this->category) : null,
        ];
    }
}
