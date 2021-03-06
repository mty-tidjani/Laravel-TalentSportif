<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fans', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('star_id')->unsigned();
            $table->integer('fan_id')->unsigned();
            $table->smallInteger('star_follow')->default(false);
            $table->smallInteger('fan_follow')->default(false);
            $table->smallInteger('star_block')->default(false);
            $table->smallInteger('fan_block')->default(false);
            $table->foreign('star_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('fan_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('fans');
    }
}
