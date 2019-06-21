<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->char('name', 50)->default('')->comment('用户名');
            $table->char('pass', 32)->default('')->comment('密码');
            $table->char('salt', 32)->default('')->comment('盐');
            $table->char('google2fa_key', 128)->default('')->comment('谷歌两步验证密钥');
            $table->unsignedSmallInteger('login_count')->default(0)->comment('登陆次数');
            $table->string('token', 32)->default('')->comment('记住密码生成的随机数');
            $table->char('login_ip', 15)->default('')->comment('登陆IP');
            $table->integer('created_at')->default(0)->comment('创建时间');
            $table->integer('updated_at')->default(0)->comment('更新时间');
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
