<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;



class VendorProducts extends Model
{
    use HasFactory,UuidTrait, Searchable, SoftDeletes;

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

protected $table = 'vendorProducts';

    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $guarded = [];

}
