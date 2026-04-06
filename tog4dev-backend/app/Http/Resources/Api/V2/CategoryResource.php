<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title,
            'title_en' => $this->title_en,
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
            'description_ar' => $this->description,
            'description_en' => $this->description_en,
            'hero_title_ar' => $this->hero_title,
            'hero_title_en' => $this->hero_title_en,
            'hero_description_ar' => $this->hero_description,
            'hero_description_en' => $this->hero_description_en,
            'image_ar' => $this->image,
            'image_en' => $this->image_en,
            'hero_image_ar' => $this->hero,
            'hero_image_en' => $this->hero_en,
            'type' => $this->type,
            'status' => (int) $this->status,
            'is_all_option' => (bool) $this->all_option,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null
        ];
    }
}
