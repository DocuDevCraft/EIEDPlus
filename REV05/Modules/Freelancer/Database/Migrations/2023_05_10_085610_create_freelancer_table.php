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
        Schema::create('freelancer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('meli_code', 255)->nullable()->unique();
            $table->string('cardserialno')->nullable();
            $table->string('shenasnameh', 255)->nullable()->unique();
            $table->string('mahale_sodoor')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->string('sarbazi')->nullable();
            $table->bigInteger('sarbazi_file')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('website')->nullable();
            $table->string('birthday')->nullable();
            $table->string('birthday_miladi')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('biography')->nullable();
            $table->boolean('tax')->nullable();
            $table->string('tax_value')->nullable();
            $table->bigInteger('tax_file')->nullable();
            $table->text('shaba')->nullable();
            $table->bigInteger('resume_file')->nullable();
            $table->string('accept_rules')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelancer');
    }
};
