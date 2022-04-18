<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createproductstable extends Migration
{

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("user_id");
            $table->date("expiration_date");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->delete("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
