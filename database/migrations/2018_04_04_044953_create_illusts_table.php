<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIllustsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('illusts', function (Blueprint $table) {
            $table->integer('id')->unsigned()->comment('Pixiv workid');
            $table->text('title')->comment('作品标题');
            $table->text('caption')->comment('作品介绍');
            $table->integer('pixiv_id')->unsigned()->comment('作者PixivId');
            $table->text('thumbnail')->comment('略缩图');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('pixiv_id')->references('id')->on('pixiv_users')
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
        Schema::dropIfExists('illusts');
    }
}
