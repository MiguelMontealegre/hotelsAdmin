<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\UuidTrait;
use App\Models\Media\Media;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory, UuidTrait, Searchable, SoftDeletes;


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


	protected $fillable = ['name', 'description', 'country', 'city', 'address'];


    /**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name'           => $this->title,
			'description'           => $this->description,
			'country'           => $this->country,
            'city'           => $this->city,
        ];

    }//end toSearchableArray()

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

    /**
     * Get specifications
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'hotelId');
    }//end specifications()
}
