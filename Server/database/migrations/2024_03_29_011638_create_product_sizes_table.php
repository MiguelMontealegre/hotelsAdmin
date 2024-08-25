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
        Schema::create('productSizes', function (Blueprint $table) {
            $table->uuid('id')->primary();

			$table->string('value');

			$table->uuid('productId')->nullable();
			$table->foreign('productId')
				->references('id')
				->on('products')
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
        Schema::dropIfExists('productSizes');
    }
};
