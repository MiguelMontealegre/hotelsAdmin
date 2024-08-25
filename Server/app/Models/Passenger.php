<?php

namespace App\Models;

use App\Models\Order;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
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



    protected $fillable = [
        'identification',
		'name',
		'birthdate', 
		'email', 
        'phone',
        'idType',
        'gender',
        'orderId'
	];


	/**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
		return [
            'name'           => $this->name,
        ];

    }//end toSearchableArray()



	/**
     * Get user relation
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orderId');

    }//end user()

}
