<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'ar');

        $pick = function ($ar, $en) use ($lang) {
            return $lang === 'en' ? ($en ?: $ar) : ($ar ?: $en);
        };

        return [
            'id' => $this->id,
            'section_key' => $this->section_key,
            'title' => $pick($this->title, $this->title_en),
            'subtitle' => $pick($this->subtitle, $this->subtitle_en),
            'body' => $pick($this->body, $this->body_en),
            'image' => $this->image,
            'video_url' => $this->video_url,
            'cta_text' => $pick($this->cta_text, $this->cta_text_en),
            'cta_link' => $pick($this->cta_link, $this->cta_link_en),
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
