<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('用户id')->index();
            $table->integer('order_id')->comment('关联主订单ID')->index();
            $table->string('start', 16)->comment('订单出发地坐标');
            $table->string('start_text', 64)->comment('订单出发地名称');
            $table->string('end', 16)->comment('订单目的地坐标');
            $table->string('end_text', 64)->comment('订单目的地坐标名称');
            $table->string('distance', 16)->comment('订单距离');

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
        Schema::dropIfExists('destinations');
    }
}
