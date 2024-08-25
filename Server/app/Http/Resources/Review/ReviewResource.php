<?php
declare(strict_types=1);

namespace App\Http\Resources\Review;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;


class ReviewResource extends JsonResource
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
            'content'          => $this->resource->content,
			'valoration'          => $this->resource->valoration,
			'pin' => $this->resource->pin,

			'user'          => UserResource::make($this->resource->user),

            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
