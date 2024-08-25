<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CuponResource extends JsonResource
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
            'id' => $this->resource->id,
            'discount' => $this->resource->discount,
            'code' => $this->resource->code,
            'availableQuantity' => $this->resource->availableQuantity,
            'expirationDate' => $this->resource->expirationDate,
            'products' => $this->resource->products,
            'categories' => $this->resource->categories,
            'updatedAt' => $this->resource->updatedAt,
            'createdAt' => $this->resource->createdAt,
        ];
        
    }
}
