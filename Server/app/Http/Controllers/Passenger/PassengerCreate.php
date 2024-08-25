<?php
declare(strict_types=1);

namespace App\Http\Controllers\Passenger;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Passenger\PassengerRequest;
use App\Http\Resources\Passenger\PassengerResource;
use App\Models\Passenger;


class PassengerCreate extends BaseCreate
{

    /**
     * @var string
     */
    public string $modelClass = Passenger::class;

    /**
     * @var string
     */
    public string $resourceClass = PassengerResource::class;

    /**
     * @var string
     */
    public string $requestClass = PassengerRequest::class;

}//end class
