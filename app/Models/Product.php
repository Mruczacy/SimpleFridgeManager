<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Fridge;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory;

    protected $fillable = [
        'name',
        'expiration_date',
        'product_category_id',
        'fridge_id',
    ];

    public function fridge() : BelongsTo {
        return $this->belongsTo(Fridge::class);
    }

    public function category() : BelongsTo {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function isActualCategory(ProductCategory $category) : bool {
        return $this->product_category_id == $category->id;
    }

    public function isActualFridge(Fridge $fridge) : bool {
        return $this->fridge_id == $fridge->id;
    }

}
?>
