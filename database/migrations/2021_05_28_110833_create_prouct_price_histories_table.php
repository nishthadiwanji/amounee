<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProuctPriceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prouct_price_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->double('base_price',8,2);
            $table->string('applied_commision');
            $table->double('applied_commission_number',8,2);
            $table->string('applied_commision_type');
            $table->double('commision_amount',8,2);
            $table->double('selling_price',8,2);
            $table->double('tax',8,2);
            $table->double('mrp',8,2);


            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prouct_price_histories');
    }
}
