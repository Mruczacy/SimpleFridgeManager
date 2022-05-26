<?php

namespace App\Models\Utils;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ProductCategoryUtils {
    abstract public function getProductCategory();

    public function products() : HasMany {
        return $this->hasMany(Product::class);
    }
}

?>