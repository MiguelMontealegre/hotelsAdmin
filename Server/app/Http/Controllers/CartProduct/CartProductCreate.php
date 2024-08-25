<?php
declare(strict_types=1);

namespace App\Http\Controllers\CartProduct;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\CartProduct\CartProductRequest;
use App\Http\Resources\CartProduct\CartProductResource;
use App\Models\CartProduct;


class CartProductCreate extends BaseCreate
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
     * @var string
     */
    public string $requestClass = CartProductRequest::class;

}//end class
