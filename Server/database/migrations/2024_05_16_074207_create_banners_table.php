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
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->nullable();
            $table->timestamp('startDate');
            $table->timestamp('endDate');

            $table->uuid('mediaId');
			$table->foreign('mediaId')
				->references('id')
				->on('media')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->uuid('mediaSmId');
			$table->foreign('mediaSmId')
				->references('id')
				->on('media')
				->onDelete('restrict')
				->onUpdate('restrict');

            $table->string('link')->nullable();
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
        Schema::dropIfExists('banners');
    }
};
