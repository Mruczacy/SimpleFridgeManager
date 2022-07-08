<?php

declare(strict_types=1);

use App\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategory extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignIdFor(ProductCategory::class)->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_product_category_id_foreign');
            $table->dropColumn("product_category_id");
        });
        Schema::dropIfExists('product_categories');
    }
}
