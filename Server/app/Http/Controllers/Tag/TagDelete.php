<?php
declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Base\BaseDelete;
use App\Models\Tag;

class TagDelete extends BaseDelete
{

    /**
     * @var string
     */
    public string $modelClass = Tag::class;

}//end class
