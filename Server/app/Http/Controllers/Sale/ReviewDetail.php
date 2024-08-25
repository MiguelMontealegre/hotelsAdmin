<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\SaleResource;

use App\Models\Sale;
use Illuminate\Contracts\Container\Container;

class SaleDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Sale::class;

    /**
     * @var string
     */
    public string $resourceClass = SaleResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
