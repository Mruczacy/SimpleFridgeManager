<?php

namespace App\Models\Traits;
use App\Models\ProductCategory;
use App\Models\Fridge;

trait ProductTrait {
    abstract public function getProduct();

    public function fridge()
    {
        return $this->getProduct()->belongsTo(Fridge::class);
    }

    public function category()
    {
        return $this->getProduct()->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function isActualCategory(ProductCategory $category)
    {
        return $this->getProduct()->product_category_id == $category->id;
    }

    public function isActualFridge(Fridge $fridge)
    {
        return $this->getProduct()->fridge_id == $fridge->id;
    }
}

?>
