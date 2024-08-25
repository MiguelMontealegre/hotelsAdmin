<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use  App\Models\Media\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Banner extends Model
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

protected $table = 'banners';



    /**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = ['title', 'startDate', 'endDate', 'mediaId', 'mediaSmId', 'link'];


	/**
     * Get Logo
     *
     * @return BelongsTo
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mediaId');
    }//end logoMedia()


	/**
     * Get Logo
     *
     * @return BelongsTo
     */
    public function mediaSm(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mediaSmId');
    }//end logoMedia()
}

