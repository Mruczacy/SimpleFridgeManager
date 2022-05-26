<?php

namespace App\Models\Utils;
use App\Models\ProductCategory;
use App\Models\Fridge;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProductUtils {
    abstract public function getProduct();

    public function fridge() : BelongsTo {
        return $this->getProduct()->belongsTo(Fridge::class);
    }

    public function category() : BelongsTo {
        return $this->getProduct()->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function isActualCategory(ProductCategory $category) : bool {
        return $this->getProduct()->product_category_id == $category->id;
    }

    public function isActualFridge(Fridge $fridge) : bool {
        return $this->getProduct()->fridge_id == $fridge->id;
    }
}

?>
