<?php

namespace App\Models\Traits;
use App\Models\Product;
use App\Models\User;

trait FridgeTrait {
    abstract public function getFridge();

    public function products()
    {
        return $this->getFridge()->hasMany(Product::class);
    }

    public function users(){
        return $this->getFridge()->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id')->withPivot('is_manager');
    }

    public function managers(){
        return $this->getFridge()->users()->wherePivot('is_manager', true);
    }

    public function owner(){
        return $this->getFridge()->belongsTo(User::class, 'owner_id');
    }
}
?>
