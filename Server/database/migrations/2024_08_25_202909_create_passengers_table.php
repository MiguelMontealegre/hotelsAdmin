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
        Schema::create('passengers', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->string('name');
            $table->dateTime('birthdate');
            $table->string('email');
            $table->string('phone');
            $table->string('idType');
            $table->string('identification');
            $table->string('gender');

            $table->uuid('orderId')->nullable();
			$table->foreign('orderId')
				->references('id')
				->on('orders')
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
        Schema::dropIfExists('passengers');
    }
};
