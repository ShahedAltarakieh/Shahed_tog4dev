<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'ar');

        return [
            'id' => $this->id,
            'section_key' => $this->section_key,
            'title' => $lang === 'en' ? ($this->title_en ?: $this->title) : $this->title,
            'subtitle' => $lang === 'en' ? ($this->subtitle_en ?: $this->subtitle) : $this->subtitle,
            'body' => $lang === 'en' ? ($this->body_en ?: $this->body) : $this->body,
            'image' => $this->image,
            'video_url' => $this->video_url,
            'cta_text' => $lang === 'en' ? ($this->cta_text_en ?: $this->cta_text) : $this->cta_text,
            'cta_link' => $lang === 'en' ? ($this->cta_link_en ?: $this->cta_link) : $this->cta_link,
            'layout' => $this->layout,
            'settings' => $this->settings ?? [],
            'sort_order' => $this->sort_order,
            'is_visible' => $this->is_visible,
            'items' => AboutSectionItemResource::collection($this->whenLoaded('items', function () {
                return $this->items->where('is_visible', true)->sortBy('sort_order')->values();
            }, [])),
        ];
    }
}
