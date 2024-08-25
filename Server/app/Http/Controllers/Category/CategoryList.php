<?php
declare(strict_types=1);

namespace App\Http\Controllers\Category;

use App\Models\Category;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\Category\CategoryResource;

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
class CategoryList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = Category::class;

    /**
     * @var string
     */
    public string $resourceClass = CategoryResource::class;

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
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
