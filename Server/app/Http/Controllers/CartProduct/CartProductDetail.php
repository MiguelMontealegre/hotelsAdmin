<?php
declare(strict_types=1);

namespace App\Http\Controllers\CartProduct;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\CartProduct\CartProductResource;
use App\Models\CartProduct;
use Illuminate\Contracts\Container\Container;

class CartProductDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = CartProduct::class;

    /**
     * @var string
     */
    public string $resourceClass = CartProductResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
