<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Fridge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFridgeToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('fridgesToUsers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('is_owner');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Fridge::class)->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fridgesToUsers');
    }
}
