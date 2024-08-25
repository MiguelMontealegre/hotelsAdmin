<?php
declare(strict_types=1);

namespace App\Http\Controllers\Banner;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\QueryFilters\ParentUserFilter;

class BannerUpdate extends BaseUpdate
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