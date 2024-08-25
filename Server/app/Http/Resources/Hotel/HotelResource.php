<?php

declare(strict_types=1);

namespace App\Http\Resources\Hotel;

use App\Models\Hotel;
use App\Models\HotelLike;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Hotel\HotelColor\HotelColorResource;

/**
 * Class UserResource
 *
 * @category Resources
 * @package  App\Http\Resources

 */
class HotelResource extends JsonResource
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
			'name'         => $this->resource->name,
			'description'          => $this->resource->description,
			'media'          => $this->resource->media,

			'country'          => $this->resource->country,
			'city'          => $this->resource->city,
			'address'          => $this->resource->address,

			'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'archivedAt'         => $this->resource->archivedAt,
			'deletedAt'         => $this->resource->deletedAt,
		];

		return $ary;
	} //end toArray()


}//end class
