<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTraceIdToBehavior extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behavior', function (Blueprint $table) {
            $table->unsignedTinyInteger('trace_id')
                ->default(0)
                ->comment('埋点平台，1：庄园，2：红包');

            $table->index([
                'trace_id',
                'user_id',
                'bhv_type'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('behavior', function (Blueprint $table) {
            //
        });
    }
}
