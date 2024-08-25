<?php
declare(strict_types=1);

namespace App\Http\Controllers\OrderProduct;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\OrderProduct\OrderProductRequest;
use App\Http\Resources\OrderProduct\OrderProductResource;
use App\Models\OrderProduct;

class OrderProductUpdate extends BaseUpdate
{

    /**
     * @var string
     */
    public string $modelClass = OrderProduct::class;

    /**
     * @var string
     */
    public string $resourceClass = OrderProductResource::class;

    /**
     * @var string
     */
    public string $requestClass = OrderProductRequest::class;

}//end class
