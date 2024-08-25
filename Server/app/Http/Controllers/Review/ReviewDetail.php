<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;
use Illuminate\Contracts\Container\Container;

class ReviewDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Review::class;

    /**
     * @var string
     */
    public string $resourceClass = ReviewResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
