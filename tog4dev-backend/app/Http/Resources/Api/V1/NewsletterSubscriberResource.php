<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterSubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status === 1 ? 'active' : 'inactive',
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
