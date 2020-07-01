<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTaskState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_state', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->comment('用户id');
            $table->unsignedBigInteger('plant_id')->comment('植物id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_state', function (Blueprint $table) {
            //
        });
    }
}
