<?php
declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;

class OrderUpdate extends BaseUpdate
{

    /**
     * @var string
     */
    public string $modelClass = Order::class;

    /**
     * @var string
     */
    public string $resourceClass = OrderResource::class;

    /**
     * @var string
     */
    public string $requestClass = OrderRequest::class;

}//end class
