<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorProductsResource extends JsonResource
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
            'companyName' => $this->companyName,
            'contactName' => $this->contactName,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'productName' => $this->productName,
            'sellingPrice' => $this->sellingPrice,
            'wholesalePrice' => $this->wholesalePrice,
            'minQuantity' => $this->minQuantity,
            'productDescription' => $this->productDescription,
            'fileURL' => $this->fileURL,
            'createdAt' => $this->createdAt,
        ];
    }
}
