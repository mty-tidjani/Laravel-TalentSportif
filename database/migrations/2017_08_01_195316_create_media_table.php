<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function(Blueprint $table) {
            $table->increments('id');
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->string('discr');
            $table->boolean('del')->default(false);
            $table->integer('user_id')->unsigned();
            $table->integer('album_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('album_id')->references('id')->on('albums');
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
        Schema::drop('media');
    }
}
