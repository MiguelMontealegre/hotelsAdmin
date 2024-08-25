<?php
declare(strict_types=1);


namespace App\Http\Controllers\Cupon;

use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use App\QueryFilters\Pagination;
use App\Models\Cupon;
use App\Http\Resources\CuponResource;
use Illuminate\Contracts\Container\Container;

/**
 * Class MediaList
 *
 * @extends  ScoutList ScoutList
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Catalina Cardona <itcataca@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class CuponList extends ScoutList
{

    /**
     * @var string
     */
    public string $modelClass = Cupon::class;

    /**
     * @var string
     */
    public string $resourceClass = CuponResource::class;

    /**
     * @var string
     */
    public string $requestClass = PaginationRequest::class;



    public function __construct(Container $container)
    {
        $this->filters = [
            
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
