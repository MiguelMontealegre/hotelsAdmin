<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'description' => $this->description,
            'media' => $this->media, 
            'link' => $this->link,
            'discountPercentage' => $this->discountPercentage, 
            'productId' => $this->productId, 
            'updatedAt' => $this->updatedAt,
            'createdAt' => $this->createdAt,
        ];
    }
}
