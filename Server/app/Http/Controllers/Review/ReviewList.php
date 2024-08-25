<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Models\Review;
use App\QueryFilters\ReviewFilter;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\Review\ReviewResource;
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
class ReviewList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = Review::class;

    /**
     * @var string
     */
    public string $resourceClass = ReviewResource::class;

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
			ReviewFilter::class
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
