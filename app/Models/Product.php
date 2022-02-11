<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductCategory;

class Product extends Model {

    use HasFactory;

    protected $fillable = [
        'name',
        'expiration_date',
        'product_category_id',
        'fridge_id',
    ];

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
?>
