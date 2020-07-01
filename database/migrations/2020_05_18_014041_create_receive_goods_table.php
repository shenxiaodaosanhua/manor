<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiveGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('用户id');
            $table->unsignedBigInteger('goods_id')->default(0)->comment('礼品id');
            $table->string('name', 50)->default('')->comment('收获人姓名');
            $table->string('mobile', 20)->default('')->comment('收货人手机号码');
            $table->string('address', 60)->default('')->comment('收货人地址');
            $table->string('tracking_number', 60)->default('')->comment('物流快递单号');
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
        Schema::dropIfExists('receive_goods');
    }
}
