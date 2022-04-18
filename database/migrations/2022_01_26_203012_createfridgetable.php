<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createfridgetable extends Migration
{

    public function up()
    {
        Schema::create('fridges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
        Schema::create('fridgesToUsers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('fridge_id');
            $table->integer('is_owner');
            $table->foreign('user_id')->references('id')->on('users')->delete('cascade');
            $table->foreign('fridge_id')->references('id')->on('fridges')->delete('cascade');

        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
            $table->unsignedBigInteger('fridge_id');
            $table->foreign('fridge_id')->references('id')->on('fridges')->delete('cascade');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['fridge_id']);
            $table->dropColumn(['fridge_id']);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->delete('cascade');
        });
        Schema::dropIfExists('fridgesToUsers');
        Schema::dropIfExists('fridges');
    }
}
