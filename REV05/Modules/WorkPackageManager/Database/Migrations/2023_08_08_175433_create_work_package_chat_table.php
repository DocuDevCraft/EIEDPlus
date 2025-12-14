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
        Schema::create('work_package_chat', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_package_id');
            $table->bigInteger('user_id');
            $table->bigInteger('replay_to_user_id')->nullable();
            $table->text('message')->nullable();
            $table->text('attachment')->nullable();
            $table->string('type');
            $table->string('status');
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
        Schema::dropIfExists('work_package_chat');
    }
};
