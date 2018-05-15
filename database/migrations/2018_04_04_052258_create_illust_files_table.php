<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIllustFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('illust_files', function (Blueprint $table) {
            $table->increments('id')->comment('file id');
            $table->integer('illust_id')->unsigned()->comment('work id');
            $table->string('hash', 150)->comment('文件 hash');
            $table->string('filename')->comment('文件名');
            $table->string('origin_filename')->nullbale()->default(null)->comment('原始文件名');
            $table->float('width')->default(null)->comment('图片宽度');
            $table->float('height')->default(null)->comment('图片高度');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('hash');
            $table->foreign('illust_id')->references('id')->on('illusts')
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
        Schema::dropIfExists('illust_files');
    }
}
