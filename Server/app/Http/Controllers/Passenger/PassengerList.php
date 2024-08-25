<?php
declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Models\Passenger;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\Passenger\PassengerResource;

class PassengerList extends ScoutList
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
    public string $requestClass = PaginationRequest::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->filters = [
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
