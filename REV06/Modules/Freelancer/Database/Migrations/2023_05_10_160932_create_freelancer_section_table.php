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
        Schema::create('freelancer_section', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id');
            $table->bigInteger('section_id');
            $table->bigInteger('subsection_id')->nullable()->default(0);
            $table->bigInteger('division_id')->nullable()->default(0);
            $table->json('suggest_grade_data')->nullable();
            $table->json('suggest_grade_message')->nullable();
            $table->json('grade_data')->nullable();
            $table->json('grade_message')->nullable();
            $table->float('suggest_grade')->nullable();
            $table->float('grade')->nullable();
            $table->float('final_grade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freelancer_section');
    }
};
