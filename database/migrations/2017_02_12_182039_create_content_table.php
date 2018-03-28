<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->increments('content_id')->unsigned();
            $table->integer('free_order')->unsigned();
            $table->integer('stopicid')->unsigned();
            $table->integer('parentid')->unsigned();
            $table->integer('groupid')->unsigned();
            $table->integer('controllerid')->unsigned();
            $table->string('title');
            $table->text('content');
            $table->boolean('pintonav');
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
        Schema::dropIfExists('content');
    }
}
