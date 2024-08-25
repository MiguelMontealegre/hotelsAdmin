<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Container\Container;

/**
 * Class ProductDetail
 *
 * @extends  BaseDetail BaseDetail
 * @category Controllers
 * @package  App\Http\Controllers\Product
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class ProductDetail extends BaseDetail
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
