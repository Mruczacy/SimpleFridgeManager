<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createproductcategory extends Migration
{

    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger("product_category_id")->nullable();
            $table->foreign("product_category_id")->references("id")->on("product_categories")->delete("cascade");
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
            $table->dropColumn("product_category_id");
        });
        Schema::dropIfExists('product_categories');
    }
}
