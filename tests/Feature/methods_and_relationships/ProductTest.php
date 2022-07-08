<?php

namespace Tests\Feature\Methods_And_Relationships;

use App\Models\Fridge;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testFridgeMethod()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $this->assertEquals($fridge->id, $product->fridge->id);
    }

    public function testTresholds()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
            'expiration_date' => now()->addDays($fridge->throw_it_out_treshold - 1),
        ]);
        $this->assertTrue($product->trashTresholdHit());
        $this->assertFalse($product->asapTresholdHit());
        $this->assertFalse($product->inNearFutureTresholdHit());
        $product->expiration_date = now()->addDays($fridge->asap_treshold);
        $product->save();
        $this->assertFalse($product->trashTresholdHit());
        $this->assertTrue($product->asapTresholdHit());
        $this->assertFalse($product->inNearFutureTresholdHit());
        $product->expiration_date = now()->addDays($fridge->in_near_future_treshold);
        $product->save();
        $this->assertFalse($product->trashTresholdHit());
        $this->assertFalse($product->asapTresholdHit());
        $this->assertTrue($product->inNearFutureTresholdHit());
        $product->expiration_date = now()->addDays($fridge->in_near_future_treshold + 1);
        $product->save();
        $this->assertFalse($product->trashTresholdHit());
        $this->assertFalse($product->asapTresholdHit());
        $this->assertFalse($product->inNearFutureTresholdHit());
    }

    public function testCategory()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function testIsActualCategory()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'fridge_id' => $fridge->id,
            'product_category_id' => $category->id,
        ]);
        $this->assertTrue($product->isActualCategory($category));
        $this->assertFalse($product->isActualCategory(ProductCategory::factory()->create()));
    }

    public function testIsActualFridge()
    {
        $user = User::factory()->create();
        $fridge = Fridge::factory()->create(['owner_id' => $user->id]);
        $product = Product::factory()->create(['fridge_id' => $fridge->id]);
        $this->assertTrue($product->isActualFridge($fridge));
        $this->assertFalse($product->isActualFridge(Fridge::factory()->create()));
    }
}
