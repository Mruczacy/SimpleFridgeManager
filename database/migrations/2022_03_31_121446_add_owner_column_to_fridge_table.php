<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerColumnToFridgeTable extends Migration
{

    public function up()
    {

        Schema::table('fridgesToUsers', function(Blueprint $table) {
            $table->renameColumn('is_owner', 'is_manager');
        });

        Schema::table('fridges', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->delete('cascade');
        });


    }

    public function down()
    {
        Schema::table('fridges', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['owner_id']);
        });

        Schema::table('fridgesToUsers', function(Blueprint $table) {
            $table->renameColumn('is_manager', 'is_owner');
        });
    }
}
