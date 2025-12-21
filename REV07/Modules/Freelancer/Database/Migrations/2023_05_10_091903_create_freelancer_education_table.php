<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('field_of_study');
            $table->string('orientation');
            $table->string('education_level');
            $table->string('university');
            $table->string('at_time');
            $table->string('to_time')->nullable();
            $table->string('country');
            $table->string('city');
            $table->string('gpa')->nullable();
            $table->bigInteger('education_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelancer_education');
    }
};
