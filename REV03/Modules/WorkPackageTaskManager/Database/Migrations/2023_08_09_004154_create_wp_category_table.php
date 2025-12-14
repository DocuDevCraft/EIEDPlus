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
        Schema::create('wp_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_package_id');
            $table->bigInteger('activity_id');
            $table->text('title');
            $table->integer('stage')->nullable();
            $table->integer('price_percentage');
            $table->text('due_date');
            $table->string('status')->nullable();
            $table->timestamp('activation_at')->nullable();
            $table->timestamp('completed_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_category');
    }
};
