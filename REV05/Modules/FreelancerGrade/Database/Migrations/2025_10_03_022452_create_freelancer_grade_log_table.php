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
        Schema::create('freelancer_grade_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('freelancer_id');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('freelancer_section_id');
            $table->foreign('freelancer_section_id')->references('id')->on('freelancer_section')->onDelete('cascade');
            $table->json('grade_data');
            $table->json('grade_message');
            $table->float('from_grade')->nullable();
            $table->float('to_grade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelancer_grade_log');
    }
};
