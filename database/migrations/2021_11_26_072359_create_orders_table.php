<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no', 32)->comment('订单编号')->index();
            $table->integer('user_id')->comment('订单创建人id')->index();
            $table->integer('driver_id')->nullable()->comment('驾驶员id')->index();
            $table->tinyInteger('status')->default(0)->comment('订单状态,0-未接单，1-已接单，2-进行中，3-订单结束， 4-已取消');
            $table->tinyInteger('type')->default(0)->comment('订单类型，1-拼车，0独享');
            $table->string('order_time')->nullable()->comment('预约订单时间，为空则马上出发');
            $table->string('start_time')->nullable()->comment('订单开始时间');
            $table->string('stop_time')->nullable()->comment('订单结束时间');
            $table->mediumText('direction')->comment('规划行驶路线(JSON编码)');
            $table->string('destinations')->nullable()->comment('关联拼车人上下车表ID(JSON数组)');
            $table->string('passengers')->nullable()->comment('关联拼车人用户ID(JSON数组)');
            $table->string('start', 64)->comment('订单出发地坐标JSON');
            $table->string('start_text', 64)->comment('订单出发地名称');
            $table->string('end', 64)->comment('订单目的地坐标JSON');
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
        Schema::dropIfExists('orders');
    }
}
