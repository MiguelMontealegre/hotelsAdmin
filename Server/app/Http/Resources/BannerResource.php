<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'media' => $this->media,
            'mediaId'=> $this->mediaId,
			'mediaSm'=> $this->mediaSm,
			'mediaSmId'=> $this->mediaSmId,
            'link' => $this->link,
            'productId' => $this->productId, 
            'updatedAt' => $this->updatedAt,
            'createdAt' => $this->createdAt,
        ];
    }
}
