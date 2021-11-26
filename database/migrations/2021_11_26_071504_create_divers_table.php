<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('openid', 32)->comment('微信用户openid')->index();
            $table->string('nickname', 64)->nullable()->comment('微信昵称');
            $table->string('avatar')->nullable()->comment('用户头像');
            $table->string('name', 64)->comment('姓名');
            $table->string('mobile', 18)->comment('联系电话')->index();
            $table->string('idcard', 18)->nullable()->comment('身份证号码');
            $table->string('password', 32)->comment('登录密码');
            $table->tinyInteger('status')->default(0)->comment('状态，0-未验证身份，1-正常');
            $table->string('car_model', 64)->comment('车型');
            $table->string('car_no', 16)->comment('车牌号');
            $table->tinyInteger('car_limit')->comment('车型荷载人数');
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
        Schema::dropIfExists('divers');
    }
}
