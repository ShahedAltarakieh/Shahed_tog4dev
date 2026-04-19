<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutPageResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'ar');

        $title = $lang === 'en'
            ? ($this->meta_title_en ?: $this->meta_title)
            : ($this->meta_title ?: $this->meta_title_en);

        $description = $lang === 'en'
            ? ($this->meta_description_en ?: $this->meta_description)
            : ($this->meta_description ?: $this->meta_description_en);

        return [
            'id' => $this->id,
            'country_code' => $this->country_code,
            'status' => $this->status,
            'version' => $this->version,
            'meta' => [
                'title' => $title,
                'description' => $description,
                'og_image' => $this->og_image,
            ],
            'sections' => AboutSectionResource::collection($this->whenLoaded('sections', function () {
                return $this->sections->where('is_visible', true)->sortBy('sort_order')->values();
            }, [])),
        ];
    }
}
