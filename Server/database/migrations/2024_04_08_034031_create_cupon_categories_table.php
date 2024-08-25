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
        Schema::create('cuponCategories', function (Blueprint $table) {
            $table->uuid('id')->primary();


			$table->uuid('cuponId')->nullable();
            $table->foreign('cuponId')
                ->references('id')
                ->on('cupons')
                ->onDelete('restrict')
                ->onUpdate('restrict');

			$table->uuid('categoryId')->nullable();
			$table->foreign('categoryId')
				->references('id')
				->on('categories')
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
        Schema::dropIfExists('cuponCategories');
    }
};
