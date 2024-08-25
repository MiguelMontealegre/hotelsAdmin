<?php
declare(strict_types=1);

namespace App\Http\Controllers\CartProduct;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\CartProduct;

class CartProductDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = CartProduct::class;

}//end class
