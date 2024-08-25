<?php

namespace App\Models;

use App\Traits\UuidTrait;
use App\Models\Media\Media;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, UuidTrait, Searchable, SoftDeletes;

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


	protected $fillable = ['title', 'description', 'logoMediaId'];


	/**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'title'           => $this->title,
			'description'           => $this->description,
        ];

    }//end toSearchableArray()


	/**
     * Get Logo
     *
     * @return BelongsTo
     */
    public function logoMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logoMediaId');
    }//end logoMedia()
}
