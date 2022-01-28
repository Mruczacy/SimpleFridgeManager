<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createproductcategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productcategories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger("productcategory_id");
            $table->foreign("productcategory_id")->references("id")->on("productcategories")->delete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['productcategory_id']);
        });
        Schema::dropIfExists('productcategories');
    }
}
