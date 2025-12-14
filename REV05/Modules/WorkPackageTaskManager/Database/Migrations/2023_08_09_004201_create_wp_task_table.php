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
        Schema::create('wp_task', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_package_id');
            $table->bigInteger('category_id');
            $table->text('title');
            $table->text('desc')->nullable();
            $table->integer('price_percentage');
            $table->bigInteger('prerequisite')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_task');
    }
};
