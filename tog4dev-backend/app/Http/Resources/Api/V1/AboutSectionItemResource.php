<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutSectionItemResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->header('Accept-Language', 'ar');

        return [
            'id' => $this->id,
            'title' => $lang === 'en' ? ($this->title_en ?: $this->title) : $this->title,
            'description' => $lang === 'en' ? ($this->description_en ?: $this->description) : $this->description,
            'image' => $this->image,
            'icon' => $this->icon,
            'link' => $lang === 'en' ? ($this->link_en ?: $this->link) : $this->link,
            'value' => $this->value,
            'label' => $lang === 'en' ? ($this->label_en ?: $this->label) : $this->label,
            'social_links' => $this->social_links ?? [],
            'extra' => $this->extra ?? [],
            'sort_order' => $this->sort_order,
        ];
    }
}
