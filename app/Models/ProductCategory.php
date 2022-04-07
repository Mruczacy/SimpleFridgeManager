<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductCategory extends Model
{
    public $timestamps= false;

    use HasFactory, Traits\ProductCategoryTrait;

    protected $fillable = [
        'name',
    ];

    public function getProductCategory() {
        return $this;
    }

}
