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
        Schema::create('work_package', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid');
            $table->bigInteger('package_number');
            $table->text('title');
            $table->text('desc')->nullable();
            $table->bigInteger('section_id');
            $table->bigInteger('subsection_id');
            $table->bigInteger('division_id')->nullable();
            $table->integer('man_hour');
            $table->integer('minimum_technical_grade');
            $table->string('seniority');
            $table->string('package_time_type');
            $table->string('package_price_type');
            $table->integer('recommend_time');
            $table->integer('recommend_price');
            $table->string('winning_formula');
            $table->integer('minimum_offers');
            $table->string('coordinator');
            $table->integer('daily_fine');
            $table->integer('fine_after_day');
            $table->integer('fine_after_price');
            $table->integer('fine_after_negative');
            $table->bigInteger('attachment_for_all')->nullable();
            $table->bigInteger('attachment_for_winner')->nullable();
            $table->text('rules')->nullable();
            $table->string('offer_time');
            $table->string('wp_final_time')->nullable();
            $table->string('wp_final_price')->nullable();
            $table->string('wp_activation_time')->nullable();
            $table->string('signature')->nullable();
            $table->json('tag')->nullable();
            $table->string('work_package_type');
            $table->string('status');
            $table->string('work_package_scale')->nullable();
            $table->string('offer_list_sorting')->nullable();
            $table->string('offer_list_status')->nullable();
            $table->string('offer_list_file')->nullable();
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
        Schema::dropIfExists('work_package');
    }
};
