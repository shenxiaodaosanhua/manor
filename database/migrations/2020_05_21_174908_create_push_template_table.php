<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_template', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('module_type')->default('1')->comment('1:庄园  2：签到红包');
            $table->string('scene_code','40')->unique()->comment('推送场景code  系统调用');
            $table->string('title','40')->comment('标题');
            $table->text('content')->comment('推送内容');
            $table->string('scene_desc')->default('')->comment('推送场景描述')->nullable();
            $table->tinyInteger('push_type')->default('1')->comment('1:站内  2：push  3:站内+push');
            $table->tinyInteger('push_group_type')->default('1')->comment('推送群体  1：单点推送');
            $table->string('jump_url')->comment('跳转链接')->nullable();
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
        Schema::dropIfExists('push_template');
    }
}
