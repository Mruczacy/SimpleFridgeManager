<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Fridge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFridgeTable extends Migration
{
    public function up(): void
    {
        Schema::create('fridges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_user_id_foreign');
            $table->dropColumn('user_id');
            $table->foreignIdFor(Fridge::class)->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_fridge_id_foreign');
            $table->dropColumn('fridge_id');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
        });
        Schema::dropIfExists('fridges');
    }
}
