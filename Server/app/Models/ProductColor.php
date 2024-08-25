<?php

namespace App\Models;

use App\Traits\UuidTrait;
use App\Models\Media\Media;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductColor extends Model
{
    use UuidTrait, Searchable, SoftDeletes;

	protected $primaryKey = 'id';

    /**
     * @var string Rename created_at
     */
    const CREATED_AT = 'createdAt';
    /**
     * @var string Rename updated_at
     */
    const UPDATED_AT = 'updatedAt';
    /**
     * @var string Rename deleted_at
     */
    const DELETED_AT = 'deletedAt';


	protected $fillable = ['productId', 'value', 'color', 'mediaId'];



	/**
     * Rename Default Table Name
     *
     * @var string
     */
    protected $table = 'productColors';




	/**
     * Get company media list
     *
     * @return HasMany
     */
    public function media(): HasMany
    {
        $result = $this->hasMany(Media::class, 'mediableId')
            ->where('mediableType', get_class($this))
            ->whereNull('parentId');

        if (request()->has('notAllowSource') && !empty(request()->input('notAllowSource'))) {
            $result->whereNotIn('source', request()->input('notAllowSource'));
        }

        if (request()->has('notAllowFile') && !empty(request()->input('notAllowFile'))) {
            $result->whereNotIn('extension', request()->input('notAllowFile'));
        }

        return $result;

    }//end media()
}
