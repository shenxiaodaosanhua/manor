<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLastToBagLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bag_log', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_last')->default(0)->comment('是否最后一天 0：否 1：是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bag_log', function (Blueprint $table) {
            //
        });
    }
}
