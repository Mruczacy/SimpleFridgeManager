<?php

namespace App\Models\Traits;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait FridgeUtils {
    abstract public function getFridge();

    public function products() : HasMany {
        return $this->getFridge()->hasMany(Product::class);
    }

    public function users() : BelongsToMany {
        return $this->getFridge()->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id')->withPivot('is_manager');
    }

    public function managers() : BelongsToMany {
        return $this->getFridge()->users()->wherePivot('is_manager', true);
    }

    public function owner() : BelongsTo{
        return $this->getFridge()->belongsTo(User::class, 'owner_id');
    }
}
?>
