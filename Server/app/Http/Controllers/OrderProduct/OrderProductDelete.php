<?php
declare(strict_types=1);

namespace App\Http\Controllers\OrderProduct;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\OrderProduct;

class OrderProductDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = OrderProduct::class;

}//end class
