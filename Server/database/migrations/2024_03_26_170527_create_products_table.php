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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->string('title');
			$table->longText('description');
			$table->float('price');
			$table->float('discount')->nullable();
			$table->float('type')->nullable();
            $table->float('availableQuantity')->nullable();

            $table->uuid('hotelId');
            $table->foreign('hotelId')
                ->references('id')
                ->on('hotels')
                ->onDelete('restrict')
                ->onUpdate('restrict');
			
			$table->timestamp('archivedAt')->nullable();
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
        Schema::dropIfExists('products');
    }
};
