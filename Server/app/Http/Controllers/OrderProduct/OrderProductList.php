<?php
declare(strict_types=1);

namespace App\Http\Controllers\OrderProduct;

use App\Models\OrderProduct;
use App\QueryFilters\ParentOrderFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use App\QueryFilters\OrderProductFilter;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\OrderProduct\OrderProductResource;

class OrderProductList extends ScoutList
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
    public string $requestClass = PaginationRequest::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->filters = [
			ParentOrderFilter::class,
			OrderProductFilter::class,
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
