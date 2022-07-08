<?php

namespace Tests\Feature\Methods_And_Relationships;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testProducts()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['product_category_id' => $category->id]);
        $this->assertEquals($product->id, $category->products()->first()->id);
    }
}
