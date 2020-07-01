<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToReceiveGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receive_goods', function (Blueprint $table) {
            $table->unsignedTinyInteger('state')->default(0)->comment('礼品状态 0：无 1：已选择礼品 2：已下单 3：已发货 4：已完成 5：已超时');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_goods', function (Blueprint $table) {
            //
        });
    }
}
