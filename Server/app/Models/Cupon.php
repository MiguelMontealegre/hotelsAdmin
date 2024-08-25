<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cupon extends Model
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


    protected $table = 'cupons';

            /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


	protected $casts = [
		'discount' => 'decimal:2',
	];


	/**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'code'           => $this->code,
        ];

    }//end toSearchableArray()



    /**
     * Get Categories
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'cuponCategories', 'cuponId', 'categoryId')
            ->selectRaw('categories.id, categories.title')
            ->whereNull('cuponCategories.deletedAt');

    }//end Categories()

    /**
     * Get Products
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cuponProducts', 'cuponId', 'productId')
            ->selectRaw('products.id, products.title')
            ->whereNull('cuponProducts.deletedAt');

    }//end Products()




}
