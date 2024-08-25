<?php
declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Base\BaseDetail;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;
use Illuminate\Contracts\Container\Container;

class TagDetail extends BaseDetail
{

    /**
     * @var string
     */
    public string $modelClass = Tag::class;

    /**
     * @var string
     */
    public string $resourceClass = TagResource::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

    }//end __construct()


}//end class
