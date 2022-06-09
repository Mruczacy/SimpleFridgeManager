<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Fridge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createfridgetable extends Migration
{

    public function up(): void
    {
        Schema::create('fridges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
        Schema::create('fridgesToUsers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('is_owner');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Fridge::class);

        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->foreignIdFor(Fridge::class);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('fridge_id');
            $table->foreignIdFor(User::class);
        });
        Schema::dropIfExists('fridgesToUsers');
        Schema::dropIfExists('fridges');
    }
}
