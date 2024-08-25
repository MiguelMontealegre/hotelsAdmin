<?php

namespace App\Models;

use App\Models\Hotel;
use App\Models\Category;
use App\Traits\UuidTrait;
use App\Models\Media\Media;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Laravel\Cashier\Billable;
use Laravel\Scout\Searchable;
use App\Models\ProductFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
	use HasFactory, UuidTrait, Searchable, SoftDeletes, Billable;

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


	protected $fillable = ['title', 'availableQuantity', 'description', 'price', 'discount', 'hotelId', 'type', 'categoryId', 'tagId'];

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
			'price'           => $this->price,
        ];

    }//end toSearchableArray()


	//RELATIONSHIPS



    /**
     * Get hotel
     *
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotelId');
    }//end hotel()



	 /**
     * Get Categories
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'productCategories', 'productId', 'categoryId')
            ->selectRaw('categories.id, categories.title')
            ->whereNull('productCategories.deletedAt');

    }//end Categories()




	 /**
     * Get Tags
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'productTags', 'productId', 'tagId')
            ->selectRaw('tags.id, tags.title')
            ->whereNull('productTags.deletedAt');

    }//end Tags()



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
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class, 'productId');
    }//end specifications()



	/**
     * Get features
     *
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'productId');
    }//end features()




	/**
     * Get sizes
     *
     * @return HasMany
     */
    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class, 'productId');
    }//end sizes()



	/**
     * Get sizes
     *
     * @return HasMany
     */
    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class, 'productId');
    }//end sizes()




	 /**
     * Get Tags
     *
     * @return BelongsToMany
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'productLikes', 'productId', 'userId')
            ->whereNull('productLikes.deletedAt');

    }//end Tags()



	/**
     * Get Tags
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
		return $this->hasMany(ProductComment::class, 'productId')
			->whereNull('productComments.deletedAt');

    }//end Tags()

}
