<?php
declare(strict_types=1);

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Hotel;

/**
 * Class HotelDelete
 *
 * @extends  BaseDelete BaseDelete
 * @category Controllers
 * @package  App\Http\Controllers\Region
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class HotelDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Hotel::class;

}//end class
