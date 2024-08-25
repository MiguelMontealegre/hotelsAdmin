<?php
declare(strict_types=1);

namespace App\Http\Controllers\Tag;
use App\Http\Controllers\Base\BaseCreate;
use App\Http\Requests\Tag\TagRequest;
use App\Http\Resources\Tag\TagResource;
use App\Models\Tag;


class TagCreate extends BaseCreate
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
