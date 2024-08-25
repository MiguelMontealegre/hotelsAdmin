<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductColor\ProductColorResource;

/**
 * Class UserResource
 *
 * @category Resources
 * @package  App\Http\Resources

 */
class ProductResource extends JsonResource
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

		$checkExisting = null;
		if (auth('sanctum')->user()) {
			$user = auth('sanctum')->user();
			$checkExisting = ProductLike::where('productId', $this->resource->id)
				->where('userId', $user->id)
				->first();
			if($checkExisting){
				$checkExisting = true;
			}
		}

		$ary = [
			'id'                => $this->resource->id,
			'title'         => $this->resource->title,
			'description'          => $this->resource->description,

			'hotel' => $this->resource->hotel,

			'categories'          => $this->resource->categories,
			'tags'          => $this->resource->tags,
			'media'          => $this->resource->media,
			'specifications'          => $this->resource->specifications,
			'features'          => $this->resource->features,
			'sizes'          => $this->resource->sizes,
			'colors'          => ProductColorResource::collection($this->resource->colors),
			'likesCount'          => $this->resource->likes->count(),
			'comments'          => $this->resource->comments,

			'wholesalePrice'              => $this->resource->wholesalePrice,
			'wholesaleMinQuantity'              => $this->resource->wholesaleMinQuantity,
			'wholesaleDiscount'              => $this->resource->wholesaleDiscount,

			'userLike'				=> $checkExisting,

			'price'              => $this->resource->price,
			'pin'              => $this->resource->pin,
			'discount'             => $this->resource->discount,
			'availableQuantity'             => $this->resource->availableQuantity,
			'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'archivedAt'         => $this->resource->archivedAt,
			'deletedAt'         => $this->resource->deletedAt,
		];

		return $ary;
	} //end toArray()


}//end class
