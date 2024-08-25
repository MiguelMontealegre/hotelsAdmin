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
		Schema::create('payments', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->bigInteger('value');
			$table->string('provider');

			$table->uuid('userId')->nullable();
			$table->foreign('userId')
				->references('id')
				->on('users')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('cuponId')->nullable();
			$table->foreign('cuponId')
				->references('id')
				->on('cupons')
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
		Schema::dropIfExists('payments');
	}
};
