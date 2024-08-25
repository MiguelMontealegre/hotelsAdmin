<?php
declare(strict_types=1);


namespace App\Http\Controllers\Cupon;

use App\Http\Controllers\Base\BaseDetail;
use App\Models\Cupon;
use App\Http\Resources\CuponResource;

/**
 * Class MediaEntityDetail
 *
 * @extends  BaseDetail BaseDetail
 * @category Controllers
 * @package  App\Http\Controllers\MediaEntity
 * @author   Jignesh Parmar <jignesh.parmar@tsolife.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://tsolife.com
 */
class CuponDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Cupon::class;

    /**
     * @var string
     */
    public string $resourceClass = CuponResource::class;

}//end class
