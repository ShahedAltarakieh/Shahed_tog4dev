<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionItemResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'ar');

        $pick = function ($ar, $en) use ($lang) {
            return $lang === 'en' ? ($en ?: $ar) : ($ar ?: $en);
        };

        return [
            'id' => $this->id,
            'title' => $pick($this->title, $this->title_en),
            'description' => $pick($this->description, $this->description_en),
            'image' => $this->image,
            'icon' => $this->icon,
            'link' => $pick($this->link, $this->link_en),
            'value' => $this->value,
            'label' => $pick($this->label, $this->label_en),
            'social_links' => $this->social_links ?? [],
            'extra' => $this->extra ?? [],
            'sort_order' => $this->sort_order,
        ];
    }
}
