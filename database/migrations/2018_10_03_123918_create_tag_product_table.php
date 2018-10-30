<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_product', function (Blueprint $table) {
            $table->string('tag_id');
            $table->string('product_id');
            $table->timestamps();

            $table->primary(['tag_id', 'product_id']);

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tag_product');
    }
}
