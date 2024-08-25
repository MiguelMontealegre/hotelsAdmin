<?php
declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Product;

/**
 * Class ProductDelete
 *
 * @extends  BaseDelete BaseDelete
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class ProductDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Product::class;

}//end class
