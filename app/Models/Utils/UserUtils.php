<?php

namespace App\Models\Utils;
use App\Models\Fridge;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
trait UserUtils {

    abstract public function getUser();

    public function fridges() : BelongsToMany {
        return $this->getUser()->belongsToMany(Fridge::class, 'fridgesToUsers', 'user_id', 'fridge_id')->withPivot('is_manager');
    }

    public function managedFridges() : BelongsToMany {
        return $this->getUser()->fridges()->wherePivot('is_manager', true);
    }

    public function isFridgeManager(Fridge $fridge) : bool {
        return $fridge->managers->contains('id', $this->getUser()->id);
    }

    public function isFridgeUser(Fridge $fridge) : bool {
        return $this->getUser()->fridges->contains('id', $fridge->id);
    }

    public function isFridgeUserNoOwner(Fridge $fridge) : bool {
        return $this->getUser()->fridges->contains('id', $fridge->id) && !$this->isFridgeOwner($fridge);
    }

    public function isActualRank($role) : bool {
        return $this->getUser()->role == $role;
    }

    public function isFridgeOwner(Fridge $fridge) : bool {
        return $fridge->owner_id == $this->id;
    }

    public function ownFridges() : HasMany {
        return $this->getUser()->hasMany(Fridge::class, 'owner_id');
    }

    public function isPermittedToManage(Fridge $fridge) : bool {
        return $this->getUser()->isFridgeManager($fridge) || $this->getUser()->isFridgeOwner($fridge);
    }

    public function isEqualToAuth() : bool {
        return $this->getUser()->id == Auth::id();
    }
}

?>
