<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
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


	protected $fillable = [
		'status',
		'paymentId', 
		'firstName', 
		'lastName',
		'address',
		'addressOptional',
		'city',
		'country',
		'postalCode',
		'optional'
	];


	/**
     * Overrides Searchable fields
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
		return [
            'city'           => $this->city,
            'payments.value'           => $this->payment()->value('value'),
			'payments.provider'           => $this->payment()->value('provider'),
        ];

    }//end toSearchableArray()



	/**
     * Get user relation
     *
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'paymentId');

    }//end user()

	//relacioon de un order a muchos orderProducts

	
	public function orderProducts(){
		return $this->hasMany(OrderProduct::class,'orderId');
	}
}
