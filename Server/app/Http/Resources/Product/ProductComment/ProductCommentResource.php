<?php
declare(strict_types=1);

namespace App\Http\Resources\Product\ProductComment;

use Illuminate\Http\Request;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductComment\ProductCommentMinResource;


class ProductCommentResource extends JsonResource
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
            'content'         => $this->resource->content,
            'user'          => UserResource::make($this->resource->user),
			'parentComment'          => ProductCommentMinResource::make($this->resource->parentComment),
			'replies'				=> $this->resource->hasReplies() ? ProductCommentMinResource::collection($this->resource->comments) : [],
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
