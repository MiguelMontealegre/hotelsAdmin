<?php
declare(strict_types=1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Contracts\Container\Container;

/**
 * Class CategoryDetail
 *
 * @extends  BaseDetail BaseDetail
 * @category Controllers
 * @package  App\Http\Controllers\Category
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class CategoryDetail extends BaseDetail
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
