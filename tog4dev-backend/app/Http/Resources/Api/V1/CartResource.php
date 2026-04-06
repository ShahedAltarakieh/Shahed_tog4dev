<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $item = [];
        if($this->model_type == "App\Models\Item"){
            $item = [
              "title" => $this->item->getLocalizationTitle(),
              "description" => $this->item->getLocalizationDescription(),
              "image" => $this->item->getLocalizationImage(),
              "location" => $this->item->getLocalizationLocation(),
              "category_name" => $this->item->category->getLocalizationTitle(),
              "category_image" => $this->item->category->getLocalizationImage(),
              "category_slug" => $this->item->category->getLocalizationSlug(),
              "category_slug_ar" => $this->item->category->slug,
              "category_slug_en" => $this->item->category->slug_en,
              "category_type" => $this->item->category->type,
              "dedication_phrases" => null,
            ];
            $umraProjectId = config('app.umra_project_id');
            if ($umraProjectId && (int) $this->item_id === (int) $umraProjectId) {
                $item['dedication_phrases'] = Item::getDedicationPhrasesByLocale($request->input('locale'));
            }
        } else {
            $item = [
                "title" => $this->quickContribute->getLocalizationTitle(),
                "description" => $this->quickContribute->getLocalizationDescription(),
                "image" => $this->quickContribute->getLocalizationImage(),
                "location" => null,
                "category_name" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationTitle() : null,
                "category_image" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationImage() : null,
                "category_slug" => ($this->quickContribute->category) ? $this->quickContribute->category->getLocalizationSlug() : null,
                "category_slug_ar" => ($this->quickContribute->category) ? $this->quickContribute->category->slug : null,
                "category_slug_en" => ($this->quickContribute->category) ? $this->quickContribute->category->slug_en : null,
                "category_type" => ($this->quickContribute->category) ? $this->quickContribute->category->type : null,
                "dedication_phrases" => null,
            ];
        }
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            "type" => $this->type,
            "price" => (int) $this->price,
            "quantity" => $this->quantity,
            "has_beneficiary" => (bool) ($this->has_beneficiary ?? false),
            "dedication_names" => $this->whenLoaded('dedications', fn () => $this->dedications->pluck('name')->values()->all(), []),
            "dedication_phrase_ids" => $this->whenLoaded('dedications', fn () => $this->dedications->pluck('dedication_phrase_id')->values()->all(), []),
            "item" => $item,
        ];
    }
}
