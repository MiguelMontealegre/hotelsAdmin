<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Promotions extends Model
{
    use UuidTrait, Searchable, SoftDeletes;
    
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

protected $table = 'promotions';

    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $guarded = [];
}
