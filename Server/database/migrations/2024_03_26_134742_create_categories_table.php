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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->string('title');
			$table->string('description');

			$table->uuid('logoMediaId')->nullable();
			$table->foreign('logoMediaId')
				->references('id')
				->on('media')
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
        Schema::dropIfExists('categories');
    }
};
