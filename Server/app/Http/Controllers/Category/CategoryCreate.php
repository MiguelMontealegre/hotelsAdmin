<?php
declare(strict_types=1);

namespace App\Http\Controllers\Category;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;


class CategoryCreate extends BaseCreate
{

    /**
     * @var string
     */
    public string $modelClass = Category::class;

    /**
     * @var string
     */
    public string $resourceClass = CategoryResource::class;

    /**
     * @var string
     */
    public string $requestClass = CategoryRequest::class;

}//end class
