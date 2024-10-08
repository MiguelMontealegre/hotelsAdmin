<?php
declare(strict_types=1);

namespace App\Http\Resources\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
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
			'status' 			=> $this->resource->status,
            'date'         => $this->resource->date,
			'payment'          => $this->resource->payment,
            'emergencyContactName'          => $this->resource->emergencyContactName,
            'emergencyContactPhone'          => $this->resource->emergencyContactPhone,
            'passengers' => $this->resource->passengers,
            'createdAt'         => $this->resource->createdAt->format('Y-m-d H:i:s'),
			'deletedAt'         => $this->resource->deletedAt,
        ];

        return $ary;

    }//end toArray()


}//end class
