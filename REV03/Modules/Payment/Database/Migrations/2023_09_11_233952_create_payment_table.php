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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('work_package_id');
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('category_id');
            $table->string('amount');
            $table->string('status');
            $table->timestamps();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('work_package_id')->references('id')->on('work_package')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('wp_activity')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('wp_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
};
