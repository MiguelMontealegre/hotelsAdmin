<?php
declare(strict_types=1);

namespace App\Http\Resources\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\MediaMinResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @category Resources
 * @package  App\Http\Resources

 */
class CategoryResource extends JsonResource
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
            'title'         => $this->resource->title,
            'description'          => $this->resource->description,
			'logoMedia'            => MediaMinResource::make($this->resource->logoMedia),
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
