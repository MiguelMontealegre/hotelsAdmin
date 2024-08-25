<?php
declare(strict_types=1);

namespace App\Http\Controllers\Banner;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;

class BannerCreate extends BaseCreate
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
     * @var string
     */
    public string $requestClass = BannerRequest::class;

}//end class