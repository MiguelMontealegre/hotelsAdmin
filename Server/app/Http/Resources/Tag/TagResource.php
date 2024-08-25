<?php
declare(strict_types=1);

namespace App\Http\Resources\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class TagResource extends JsonResource
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
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
