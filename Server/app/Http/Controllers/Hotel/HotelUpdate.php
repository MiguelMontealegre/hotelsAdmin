<?php
declare(strict_types=1);

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\Hotel\HotelRequest;
use App\Http\Resources\Hotel\HotelResource;
use App\Models\Hotel;

class HotelUpdate extends BaseUpdate
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
