<?php
declare(strict_types=1);

namespace App\Http\Resources\CartProduct;

use Illuminate\Http\Request;
use App\Http\Resources\MediaMinResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;


class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request // Request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $ary = [
            'id'                => $this->resource->id,
            'quantity'          => $this->resource->quantity,
            'user'              => $this->resource->user,
			'product'           => ProductResource::make($this->resource->product),
			'size'              => $this->resource->size,
			'color'             => $this->resource->color,
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
