<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\UuidTrait;
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
     * Get specifications
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'hotelId');
    }//end specifications()
}
