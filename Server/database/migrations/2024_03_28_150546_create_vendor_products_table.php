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
        Schema::create('vendorProducts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('companyName');
            $table->string('contactName');
            $table->string('email');
            $table->string('address');
            $table->string('phone');
            $table->string('productName');
            $table->decimal('sellingPrice', 10, 2);
            $table->decimal('wholesalePrice', 10, 2);
            $table->integer('minQuantity');
            $table->text('productDescription')->nullable();
            $table->string('fileURL')->nullable();
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
        Schema::dropIfExists('vendorProducts');
    }
};
