<?php
declare(strict_types=1);

namespace App\Http\Controllers\CartProduct;

use App\Models\CartProduct;
use App\QueryFilters\ParentUserFilter;
use App\QueryFilters\ProductCartFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\CartProduct\CartProductResource;

class CartProductList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = CartProduct::class;

    /**
     * @var string
     */
    public string $resourceClass = CartProductResource::class;

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
			ProductCartFilter::class,
			ParentUserFilter::class
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
