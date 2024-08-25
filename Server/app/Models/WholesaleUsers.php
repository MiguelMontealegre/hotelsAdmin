<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use App\Models\Media\Media;

class WholesaleUsers extends Model
{
    use HasFactory,UuidTrait, Searchable;

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

    protected $table = 'wholesaleUsers';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


        /**
     * Get user relation
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');

    }//end user()

    
        /**
     * Get user relation
     *
     * @return BelongsTo
     */

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'rutMediaId');
    }
}
