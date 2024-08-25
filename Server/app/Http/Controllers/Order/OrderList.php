<?php
declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Models\Order;
use App\QueryFilters\OrderFilter;
use App\QueryFilters\ParentUserFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\Order\OrderResource;
use Illuminate\Contracts\Container\Container;

/**
 * Class UserList
 *
 * @extends  ScoutList ScoutList
 * @Order Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class OrderList extends ScoutList
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
    public string $requestClass = PaginationRequest::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->filters = [
			OrderFilter::class
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
