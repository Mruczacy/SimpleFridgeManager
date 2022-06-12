<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->date("expiration_date");
            $table->timestamps();
            $table->foreignIdFor(User::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
