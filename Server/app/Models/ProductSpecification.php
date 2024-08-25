<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSpecification extends Model
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


	protected $fillable = ['productId', 'label', 'value'];



	/**
     * Rename Default Table Name
     *
     * @var string
     */
    protected $table = 'productSpecifications';
}
