<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Traits\UuidTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductComment extends Model
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


	protected $fillable = ['id', 'userId', 'productId', 'commentId', 'content', 'replyId'];


	/**
     * Rename Default Table Name
     *
     * @var string
     */
    protected $table = 'productComments';




	public function user(){
        return $this->belongsTo(User::class, 'userId');
    }


    public function product(){
        return $this->belongsTo(Product::class, 'productId');
    }


    public function parentComment(){
        return $this->belongsTo(ProductComment::class, 'commentId');
    }


	public function hasReplies()
    {
        return $this->comments()->exists();
    }


	public function comments(){
        return $this->hasMany(ProductComment::class, 'commentId', 'id')
		->orderBy('createdAt', 'ASC');
    }


	public function reply(){
        return $this->belongsTo(ProductComment::class, 'replyId');
    }
}
