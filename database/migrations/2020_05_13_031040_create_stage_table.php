<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('阶段名称')->nullable();
            $table->unsignedInteger('stage')->index()->comment('阶段');
            $table->unsignedInteger('number')->comment('升级下一阶段次数');
            $table->unsignedTinyInteger('is_last')->comment('是否最后阶段0:否1：是');
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
        Schema::dropIfExists('stage');
    }
}
