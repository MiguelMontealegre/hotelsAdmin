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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->string('status');

			$table->string('firstName');
			$table->string('lastName');
			$table->string('address');
			$table->string('addressOptional')->nullable();
			$table->string('city');
			$table->string('country');
			$table->string('postalCode');
			$table->text('optional')->nullable();

			$table->uuid('paymentId')->nullable();
			$table->foreign('paymentId')
				->references('id')
				->on('payments')
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
        Schema::dropIfExists('orders');
    }
};
