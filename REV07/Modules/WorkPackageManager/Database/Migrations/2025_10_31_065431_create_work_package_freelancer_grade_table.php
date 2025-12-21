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
        Schema::create('work_package_freelancer_grade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_package_id');
            $table->foreign('work_package_id')->references('id')->on('work_package')->onDelete('cascade');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('section')->onDelete('cascade');
            $table->unsignedBigInteger('freelancer_id');
            $table->foreign('freelancer_id')->references('id')->on('freelancer')->onDelete('cascade');
            $table->json('suggest_grade_data')->nullable();
            $table->longText('suggest_grade_message')->nullable();
            $table->json('grade_data')->nullable();
            $table->longText('grade_message')->nullable();
            $table->float('suggest_grade')->nullable();
            $table->float('grade')->nullable();
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
        Schema::dropIfExists('work_package_freelancer_grade');
    }
};
