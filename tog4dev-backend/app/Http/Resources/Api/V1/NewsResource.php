<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        $body = $this->getLocalizationBody();
        $readingTime = calculateReadingTime($body);
        $isRecent = $this->created_at && $this->created_at->greaterThanOrEqualTo(now()->subDays(7));

        return [
            'id' => $this->id,
            'title' => $this->getLocalizationTitle(),
            'slug' => $this->getLocalizationSlug(),
            'slug_ar' => $this->slug,
            'slug_en' => $this->slug_en,
            'excerpt' => $this->getLocalizationExcerpt(),
            'body' => $body,
            'image' => $this->image,
            'image_tablet' => $this->image_tablet,
            'image_mobile' => $this->image_mobile,
            'is_recent' => $isRecent,
            'reading_time' => $readingTime,
            'published_at' => $this->published_at ? $this->published_at->toIso8601String() : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'category' => $this->category ? new NewsCategoryResource($this->category) : null,
        ];
    }
}
