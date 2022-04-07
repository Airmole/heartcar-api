<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('openid', 32)->comment('微信用户openid')->index();
            $table->string('nickname', 64)->nullable()->comment('微信昵称');
            $table->string('avatar')->nullable()->comment('用户头像');
            $table->string('name', 64)->comment('姓名');
            $table->string('mobile', 18)->comment('联系电话')->unique();
            $table->string('idcard', 18)->nullable()->comment('身份证号码');
            $table->string('password', 32)->comment('登录密码');
            $table->tinyInteger('status')->default(0)->comment('状态，0-未验证身份，1-正常');
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
        Schema::dropIfExists('users');
    }
}
