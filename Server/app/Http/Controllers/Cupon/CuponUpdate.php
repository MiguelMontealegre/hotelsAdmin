<?php
declare(strict_types=1);


namespace App\Http\Controllers\Cupon;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\CuponRequest;
use App\Models\Cupon;
use App\Http\Resources\CuponResource;


class CuponUpdate extends BaseUpdate
{
    /**
     * @var string
     */
    public string $modelClass = Cupon::class;

    /**
     * @var string
     */
    public string $resourceClass = CuponResource::class;

    /**
     * @var string
     */
    public string $requestClass = CuponRequest::class; 

}//end class
