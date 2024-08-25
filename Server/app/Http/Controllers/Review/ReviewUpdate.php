<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\Review\ReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;

class ReviewUpdate extends BaseUpdate
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
     * @var string
     */
    public string $requestClass = ReviewRequest::class;

}//end class
