<?php
declare(strict_types=1);

namespace App\Http\Controllers\Wholesale;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use App\Models\WholesaleUsers;
use App\Http\Resources\WholesaleResource;
use App\QueryFilters\Pagination\UserPagination;
use Illuminate\Contracts\Container\Container;

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
class WholesaleList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass =  WholesaleUsers::class;

    /**
     * @var string
     */
    public string $resourceClass = WholesaleResource::class;

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
            UserPagination::class,
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
