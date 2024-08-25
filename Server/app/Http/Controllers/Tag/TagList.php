<?php
declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Models\Tag;
use App\Http\Controllers\Base\ScoutList;
use App\Http\Requests\PaginationRequest;
use Illuminate\Contracts\Container\Container;
use App\Http\Resources\Tag\TagResource;

class TagList extends ScoutList
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
     * @var string
     */
    public string $requestClass = PaginationRequest::class;


    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->filters = [
        ];
        parent::__construct($container);

    }//end __construct()


}//end class
