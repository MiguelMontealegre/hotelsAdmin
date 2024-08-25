<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Traits\UuidTrait;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartProduct extends Model
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


	protected $fillable = ['userId', 'productId', 'quantity', 'sizeId', 'colorId'];


	/**
     * Rename Default Table Name
     *
     * @var string
     */
    protected $table = 'cartProducts';



	/**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'products.title'           => $this->product()->value('title'),
			'products.description'           => $this->product()->value('description'),
			'products.price'           => $this->product()->value('price'),
        ];

    }//end toSearchableArray()


	/**
     * Get user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }//end user()




	/**
     * Get product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productId');
    }//end product()



	/**
     * Get size
     *
     * @return BelongsTo
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'sizeId');
    }//end size()



	/**
     * Get color
     *
     * @return BelongsTo
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'colorId');
    }//end color()

}
