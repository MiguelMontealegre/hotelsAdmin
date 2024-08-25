<?php
declare(strict_types=1);

namespace App\Http\Controllers\Hotel;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Hotel\HotelRequest;
use App\Http\Resources\Hotel\HotelResource;
use App\Models\Hotel;


class HotelCreate extends BaseCreate
{

    /**
     * @var string
     */
    public string $modelClass = Hotel::class;

    /**
     * @var string
     */
    public string $resourceClass = HotelResource::class;

    /**
     * @var string
     */
    public string $requestClass = HotelRequest::class;

}//end class
