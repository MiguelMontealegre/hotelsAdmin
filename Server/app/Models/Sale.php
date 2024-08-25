<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;

class Sale extends Model
{
    use HasFactory, UuidTrait,Searchable;

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

    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];
}
