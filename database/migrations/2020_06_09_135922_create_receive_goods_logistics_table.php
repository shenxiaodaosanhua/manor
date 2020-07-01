<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiveGoodsLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_goods_logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('receive_id')->comment('领取id');
            $table->text('content')->comment('物流信息');
            $table->string('status')->comment('状态信息');
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
        Schema::dropIfExists('receive_goods_logistics');
    }
}
