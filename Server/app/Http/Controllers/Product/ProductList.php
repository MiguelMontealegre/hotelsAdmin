<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\QueryFilters\HotelFilter;
use App\QueryFilters\ProductsFilter;
use App\QueryFilters\JoinUserProfile;
use App\QueryFilters\CategoriesFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\Product\ProductResource;
use App\QueryFilters\Pagination\UserPagination;

/**
 * Class UserList
 *
 * @extends  ScoutList ScoutList
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class ProductList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = Product::class;

    /**
     * @var string
     */
    public string $resourceClass = ProductResource::class;

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
            HotelFilter::class,
			ProductsFilter::class,
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
