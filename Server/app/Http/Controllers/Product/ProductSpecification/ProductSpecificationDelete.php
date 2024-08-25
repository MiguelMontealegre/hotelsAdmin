<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product\ProductSpecification;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\ProductSpecification;

class ProductSpecificationDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = ProductSpecification::class;

}//end class
