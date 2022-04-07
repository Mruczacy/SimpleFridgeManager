<?php

namespace App\Models\Traits;
use App\Models\Product;

trait ProductCategoryTrait {
    abstract public function getProductCategory();

    public function products() {
        return $this->hasMany(Product::class);
    }
}

?>
