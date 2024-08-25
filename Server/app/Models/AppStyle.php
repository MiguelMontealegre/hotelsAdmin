<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
class AppStyle extends Model
{
    use UuidTrait, Searchable;

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

    protected $fillable = ['key', 'value'];
    
	/**
     * Rename Default Table Name
     *
     * @var string
     */
    protected $table = 'appStyles';



}
