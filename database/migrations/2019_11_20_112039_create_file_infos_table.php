<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('file_url');
            $table->text('thumbnail_url')->nullable();
            $table->string('name');
            $table->string('extension');
            $table->bigInteger('size');
            $table->string('type');

            $table->timestamps();
            $table->softDeletes();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_infos');
    }
}
