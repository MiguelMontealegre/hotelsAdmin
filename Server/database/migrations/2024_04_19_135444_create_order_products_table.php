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
		Schema::create('orderProducts', function (Blueprint $table) {
			$table->uuid('id')->primary();

			$table->bigInteger('quantity');

			$table->uuid('productId')->nullable();
			$table->foreign('productId')
				->references('id')
				->on('products')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('orderId')->nullable();
			$table->foreign('orderId')
				->references('id')
				->on('orders')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('sizeId')->nullable();
			$table->foreign('sizeId')
				->references('id')
				->on('productSizes')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('colorId')->nullable();
			$table->foreign('colorId')
				->references('id')
				->on('productColors')
				->onDelete('restrict')
				->onUpdate('restrict');


			$table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
			$table->timestamp('createdAt')->useCurrent();
			$table->timestamp('deletedAt')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('orderProducts');
	}
};
