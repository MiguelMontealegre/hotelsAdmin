<?php
declare(strict_types=1);


namespace App\Http\Controllers\Cupon;

use App\Http\Controllers\Base\BaseUpdate;
use App\Models\Cupon;
use App\Models\Promotions;
use App\Http\Resources\PromotionResource;

use App\Http\Requests\PromotionRequest;
class CuponUpdate extends BaseUpdate
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
    public string $requestClass = PromotionRequest::class; 

}//end class
