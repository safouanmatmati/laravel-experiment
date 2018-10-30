<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_target', function (Blueprint $table) {
            $table->string('tag_id');
            $table->string('target_id');
            $table->timestamps();

            $table->unique('tag_id', 'target_id');

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('targets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tag_target');
    }
}
