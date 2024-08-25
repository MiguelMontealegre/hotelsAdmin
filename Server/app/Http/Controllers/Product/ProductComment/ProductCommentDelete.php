<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product\ProductComment;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\ProductComment;

class ProductCommentDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = ProductComment::class;

}//end class
