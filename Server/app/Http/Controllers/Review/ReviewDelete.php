<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Review;

/**
 * Class ReviewDelete
 *
 * @extends  BaseDelete BaseDelete
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class ReviewDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Review::class;

}//end class
