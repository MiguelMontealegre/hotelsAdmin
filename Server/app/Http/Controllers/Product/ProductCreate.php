<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;


class ProductCreate extends BaseCreate
{

    /**
     * @var string
     */
    public string $modelClass = Product::class;

    /**
     * @var string
     */
    public string $resourceClass = ProductResource::class;

    /**
     * @var string
     */
    public string $requestClass = ProductRequest::class;

}//end class
