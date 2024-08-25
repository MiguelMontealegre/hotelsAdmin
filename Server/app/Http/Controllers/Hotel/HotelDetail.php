<?php
declare(strict_types=1);

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Hotel\HotelResource;
use App\Models\Hotel;
use Illuminate\Contracts\Container\Container;

/**
 * Class HotelDetail
 *
 * @extends  BaseDetail BaseDetail
 * @category Controllers
 * @package  App\Http\Controllers\Hotel
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class HotelDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Hotel::class;

    /**
     * @var string
     */
    public string $resourceClass = HotelResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
