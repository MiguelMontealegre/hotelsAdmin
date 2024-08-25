<?php
declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Order;

/**
 * Class OrderDelete
 *
 * @extends  BaseDelete BaseDelete
 * @Order Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class OrderDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Order::class;

}//end class
