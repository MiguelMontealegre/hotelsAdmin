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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('total', 10, 2);
            $table->string('status')->default('TIENDA');
            $table->string('address')->nullable();
            $table->uuid('customerId')->nullable();
            $table->foreign('customerId')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('sales');
    }
};
