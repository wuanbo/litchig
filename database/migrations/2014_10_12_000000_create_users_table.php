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
            $table->bigIncrements('id');
            $table->string('phone_number', 20)->nullable()->comment('手机号码');
            $table->string('password')->nullable()->comment('密码');
            $table->ipAddress('register_ip')->nullable()->default('::1')->comment('注册 IP');
            $table->ipAddress('last_login_ip')->nullable()->default('::1')->comment('最近登录 IP');
            $table->datetime('last_login_at')->nullable()->comment('最近登录时间');
            $table->rememberToken();
            $table->timestamps();

            $table->unique('phone_number');
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
