<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminer', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->comment('名称');
            $table->string('mobile', 18)->comment('联系电话')->index();
            $table->string('password', 32)->comment('登录密码');
            $table->string('base_fee', 16)->default('3')->nullable()->comment('每公里费用');
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
        Schema::dropIfExists('adminer');
    }
}
