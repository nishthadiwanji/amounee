<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('artisan_id')->unsigned();
            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_type');
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->date('date_payment')->nullable();
            $table->mediumText('note')->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();

            $table->foreign('artisan_id')->references('id')->on('artisans');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

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
        Schema::dropIfExists('payments');
    }
}
