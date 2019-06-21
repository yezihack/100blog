<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * 创建博客表
     * @return void
     */
    public function up()
    {

        Schema::create('blogs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户ID,与users表相关');
            $table->string('title')->default('')->comment('标题');
            $table->text('content')->commet('内容');
            $table->tinyInteger('type')->default(1)->comment('文章类型,1为原创,2为转载');
            $table->tinyInteger('is_push')->default(0)->comment('是否推送，0未，1是');
            $table->tinyInteger('status')->default(0)->comment('文章状态:1发布,2草稿');
            $table->smallInteger('first_tag_id', false, true)->default(0)->comment('首标签ID');
            $table->integer('read_count')->default(0)->comment('阅读次数');
            $table->integer('star_count')->default(0)->comment('点赞次数');
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
        Schema::dropIfExists('blogs');
    }
}
