<?php
declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Passenger;

class PassengerDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Passenger::class;

}//end class
