<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            // We will be programatically maintaining a self loop here, but we won't be making a DB relationship
            $table->unsignedInteger('parent_category_id')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable();
            $table->string('category_name'); // Unique Constraint we will be maintaining programmatically
            $table->string('wocommerce_slug')->nullable();
            $table->unsignedInteger('wocommerce_category_id')->nullable();
            $table->string('commission')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('categories');
    }
}
