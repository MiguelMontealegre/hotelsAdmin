<?php
declare(strict_types=1);

namespace App\Http\Controllers\Promotion;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Promotions;
/**
 * Class CategoryDelete
 *
 * @extends  BaseDelete BaseDelete
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class PromotionDelete extends BaseDelete
{   

    /**
     * @var string
     */
    public string $modelClass = Promotions::class;

}//end class
