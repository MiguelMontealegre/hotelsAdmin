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
        Schema::table('wholesaleUsers', function (Blueprint $table) {

            $table->dropColumn('RUT');

            $table->uuid('rutMediaId')->nullable();

            $table->foreign('rutMediaId')
            ->references('id')
            ->on('media')
            ->onDelete('restrict')
            ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wholesaleUsers', function (Blueprint $table) {

            $table->dropForeign(['rutMediaId']);

            $table->dropColumn('rutMediaId');

            $table->string('RUT');
        });
    }
};
