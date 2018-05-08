<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopHeadlinesImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_headlines_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('headline_id');
            $table->string('image_path');
             $table->foreign('headline_id')->references('id')->on('top_headlines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('top_headlines_images');
    }
}
