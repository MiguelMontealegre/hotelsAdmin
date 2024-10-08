<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'passwordResets',
            function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('createdAt')->useCurrent();
            }
        );

    }//end up()


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('passwordResets');

    }//end down()


};
