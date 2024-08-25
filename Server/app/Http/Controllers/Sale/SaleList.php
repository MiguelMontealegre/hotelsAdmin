<?php
declare(strict_types=1);

namespace App\Http\Controllers\Sale;

use App\Models\Sale;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\SaleResource;

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
class SaleList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = Sale::class;

    /**
     * @var string
     */
    public string $resourceClass = SaleResource::class;

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
