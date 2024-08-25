<?php
declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Base\BaseUpdate;
use App\Http\Requests\Tag\TagRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;

class TagUpdate extends BaseUpdate
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
    public string $requestClass = TagRequest::class;

}//end class
