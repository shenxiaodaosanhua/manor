<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBehaviorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('behavior', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bhv_type')->comment('行为类型，例如曝光、停留、点击、收藏、下载等');
            $table->string('bhv_value')->comment('行为详情，例如点击次数，停留时长，购买件数等');
            $table->string('platform')->comment('客户端平台');
            $table->string('app_version')->comment('app的版本号');
            $table->unsignedBigInteger('user_id')->comment('userid');
            $table->string('bhv_desc')->comment('行为描述');

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
        Schema::dropIfExists('behavior');
    }
}
