<?php
declare(strict_types=1);

namespace App\Http\Controllers\OrderProduct;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\OrderProduct\OrderProductResource;
use App\Models\OrderProduct;
use Illuminate\Contracts\Container\Container;

class OrderProductDetail extends BaseDetail
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
