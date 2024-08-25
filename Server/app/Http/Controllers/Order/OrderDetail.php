<?php
declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Contracts\Container\Container;

/**
 * Class OrderDetail
 *
 * @extends  BaseDetail BaseDetail
 * @Order Controllers
 * @package  App\Http\Controllers\Order
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class OrderDetail extends BaseDetail
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
