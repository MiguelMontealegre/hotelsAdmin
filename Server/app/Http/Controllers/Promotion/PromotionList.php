<?php
declare(strict_types=1);


namespace App\Http\Controllers\Promotion;


use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;

use App\Http\Resources\PromotionResource;
use Illuminate\Contracts\Container\Container;
use Carbon\Carbon;
use App\Models\Promotions;
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
class PromotionList extends ScoutList
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
     * @var string
     */
    public string $requestClass = PaginationRequest::class;



    public function __construct(Container $container)
    {
        $this->filters['active'] = function ($query) {
            $currentDate = Carbon::now()->toDateString();
            return $query->whereDate('endDate', '>=', $currentDate);
        };
        parent::__construct($container);

    }//end __construct()


}//end class
