<?php
declare(strict_types=1);

namespace App\Http\Resources\Product\ProductColor;

use Illuminate\Http\Request;
use App\Http\Resources\MediaMinResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductColorResource extends JsonResource
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
            'value'         => $this->resource->value,
			'color'         => $this->resource->color,
			'media'            => MediaMinResource::collection($this->resource->media),
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
