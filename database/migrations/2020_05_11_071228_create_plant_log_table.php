<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plant_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('plant_id')->default(0)->index()->comment('植物id');
            $table->unsignedBigInteger('user_id')->default(0)->index()->comment('用户id');
            $table->unsignedTinyInteger('type')->default(0)->comment('状态 1：首次领取 2：任务领取 3：浇水');
            $table->unsignedInteger('water')->default(0)->comment('水量变化值');
            $table->unsignedInteger('task_id')->default(0)->comment('任务id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plant_log');
    }
}
