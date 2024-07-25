<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('woocoomerce_product_id')->nullable();
            $table->unsignedBigInteger('artisan_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('sub_category_id');
            $table->text('product_name');
            $table->string('sku')->nullable();
            $table->text('short_desc');
            $table->text('long_desc');
            $table->string('material')->nullable();
            $table->integer('stock')->nullable();
            $table->enum('stock_status', config('constant.stock_status'))->default('In stock');
            $table->double('base_price',8,2);
            $table->double('product_comm_number')->nullable();
            $table->string('product_comm_type')->nullable();
            $table->double('category_comm_number')->nullable();
            $table->string('category_comm_type')->nullable();
            $table->double('artisan_comm_number')->nullable();
            $table->string('artisan_comm_type')->nullable();
            $table->double('global_comm_number')->nullable();
            $table->string('global_comm_type')->nullable();
            $table->double('selling_price',8,2);
            $table->double('commision_amount',8,2)->default(0.00);
            $table->enum('tax_status', config('constant.tax_status'))->default('Taxable');
            $table->string('tax_class');
            $table->double('tax_amount',8,2);
            $table->double('mrp',8,2);
            $table->string('hsn_code');
            $table->enum('status', config('constant.product_statues'))->default('pending');
            $table->boolean('delisted')->default(false);

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('artisan_id')->references('id')->on('artisans');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
