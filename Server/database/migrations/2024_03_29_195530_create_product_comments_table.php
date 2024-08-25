<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productComments', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->longText('content');

			$table->uuid('productId')->nullable();
			$table->foreign('productId')
				->references('id')
				->on('products')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('userId')->nullable();
			$table->foreign('userId')
				->references('id')
				->on('users')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
			$table->timestamp('createdAt')->useCurrent();
			$table->timestamp('deletedAt')->nullable();
		});


		Schema::table('productComments',function (Blueprint $table){
            $table->uuid('commentId')->nullable();
			$table->foreign('commentId')
				->references('id')
				->on('productComments')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('replyId')->nullable();
			$table->foreign('replyId')
					->references('id')
					->on('productComments')
					->onDelete('restrict')
					->onUpdate('restrict');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('productComments');
	}
};
