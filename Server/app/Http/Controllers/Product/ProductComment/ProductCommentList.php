<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product\ProductComment;

use App\Models\ProductComment;
use App\QueryFilters\ProductCommentsFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\Product\ProductComment\ProductCommentResource;
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
class ProductCommentList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = ProductComment::class;

    /**
     * @var string
     */
    public string $resourceClass = ProductCommentResource::class;

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
			ProductCommentsFilter::class,
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
