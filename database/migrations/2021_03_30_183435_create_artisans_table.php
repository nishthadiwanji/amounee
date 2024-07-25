<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtisansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artisans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('gst')->nullable();
            $table->string('country_code')->nullable()->default(+91);
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('awards')->nullable();
            $table->string('commission')->nullable();

            $table->string('approval_note')->nullable();
            $table->string('rejection_note')->nullable();
            
            $table->bigInteger('added_by')->unsigned()->nullable();

            $table->boolean('banned')->default(false);
            $table->enum('status', config('constant.artisan_statues'))->default('pending');

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('added_by')->references('id')->on('users');

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
        Schema::dropIfExists('artisans');
    }
}
