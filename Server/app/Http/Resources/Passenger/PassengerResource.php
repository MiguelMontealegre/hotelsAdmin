<?php
declare(strict_types=1);

namespace App\Http\Resources\Passenger;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PassengerResource extends JsonResource
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
            'email'          => $this->resource->email,

            'phone'          => $this->resource->phone,
            'gender'          => $this->resource->gender,
            'birthday'          => $this->resource->birthday,
            'identification'          => $this->resource->identification,
            'idType'          => $this->resource->idType,

            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
