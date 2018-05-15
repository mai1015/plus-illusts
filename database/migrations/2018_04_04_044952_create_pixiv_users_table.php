<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePixivUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pixiv_users', function (Blueprint $table) {
            $table->integer('id')->unsigned()->comment('作者Pixiv ID');
            $table->text('name')->comment('作者名字');
            $table->integer('user_id')->nullable()->unsigned()->comment('用户ID');
            $table->text('avatar_url')->comment('头像');
            $table->text('hash')->nullable()->comment('头像文件hash');
            $table->text('avatar_file')->nullable()->comment('头像文件');

            // 0 normal, 1 banned
            $table->tinyInteger('status')->default(1)->comment('账号状态');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pixiv_users');
    }
}
