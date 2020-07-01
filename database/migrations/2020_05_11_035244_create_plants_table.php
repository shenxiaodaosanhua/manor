<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->comment('关联用户id');
            $table->unsignedBigInteger('waters')->default(0)->comment('用户水量');
            $table->unsignedInteger('water_number')->default(0)->comment('浇水总次数');
            $table->unsignedInteger('stage')->default(1)->comment('阶段');
            $table->unsignedInteger('stage_number')->default(0)->comment('当前阶段浇水次数');
            $table->unsignedInteger('stage_last_number')->default(0)->comment('剩余次数');
            $table->unsignedTinyInteger('state')->default(0)->index()->comment('植物状态 0：未完成 1：已完成');

            $table->index(['user_id', 'state']);

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
        Schema::dropIfExists('plants');
    }
}
