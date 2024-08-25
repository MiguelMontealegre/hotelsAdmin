<?php
declare(strict_types=1);


namespace App\Http\Controllers\Banner;
use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Contracts\Container\Container;

class BannerDetail extends BaseDetail
{
    /**
     * @var string
     */
    public string $modelClass = Banner::class;

    /**
     * @var string
     */
    public string $resourceClass = BannerResource::class;

    
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()

}//end class

