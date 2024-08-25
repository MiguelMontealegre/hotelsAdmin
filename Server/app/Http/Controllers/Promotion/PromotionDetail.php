<?php
declare(strict_types=1);

namespace App\Http\Controllers\Promotion;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\PromotionResource;
use App\Models\Promotions;
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
class PromotionDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Promotions::class;

    /**
     * @var string
     */
    public string $resourceClass = PromotionResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
