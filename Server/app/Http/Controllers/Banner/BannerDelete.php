<?php
declare(strict_types=1);


namespace App\Http\Controllers\Banner;
use App\Http\Controllers\Base\BaseDelete;
use App\Models\Banner;

class BannerDelete extends BaseDelete
{  /**
    * @var string
    */
    public string $modelClass = Banner::class;
}