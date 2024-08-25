<?php
declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Passenger\PassengerResource;
use App\Models\Passenger;
use Illuminate\Contracts\Container\Container;

class PassengerDetail extends BaseDetail
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
