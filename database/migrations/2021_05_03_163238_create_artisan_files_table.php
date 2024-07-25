<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtisanFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artisan_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('artisan_id');
            $table->string('file_type');
            $table->unsignedBigInteger('file_id');

            $table->foreign('artisan_id')->references('id')->on('artisans');
            $table->foreign('file_id')->references('id')->on('file_infos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artisan_files');
    }
}
