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
    public function up()
    {
        Schema::create('work_package_freelancer', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('work_package_id');
            $table->unsignedBigInteger('freelancer_id');

            $table->timestamps();

            $table->foreign('work_package_id')
                ->references('id')
                ->on('work_package')
                ->onDelete('cascade');

            $table->foreign('freelancer_id')
                ->references('id')
                ->on('freelancer')
                ->onDelete('cascade');

            $table->unique(['work_package_id', 'freelancer_id']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_package_freelancer');
    }
};
