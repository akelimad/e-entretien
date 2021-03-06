<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarreersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carreers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entretien_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('userCarreer');
            $table->integer('mentor_id')->unsigned()->nullable();
            $table->string('mentorComment')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('mentor_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('entretien_id')->references('id')->on('entretiens')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('carreers');
    }
}
