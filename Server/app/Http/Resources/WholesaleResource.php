<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\MediaResource;

class WholesaleResource extends JsonResource
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
            'id' => $this->id,
            'isApproved' => $this->isApproved,
            'companyName' => $this->companyName,
            'companySize' => $this->companySize,
            'phone' => $this->phone,
            'address' => $this->address,
            'media' => MediaResource::make($this->media),
            'active' => $this->active,
            'userId' => $this->userId,
            'user' => UserResource::make($this->user),
            'updatedAt' => $this->updatedAt,
            'createdAt' => $this->createdAt,
            'deletedAt' => $this->deletedAt,
        ];
    }
}
