<?php

namespace App\Models\Traits;
use App\Models\Fridge;

trait UserTrait {

    abstract public function getUser();

    public function fridges(){
        return $this->getUser()->belongsToMany(Fridge::class, 'fridgesToUsers', 'user_id', 'fridge_id')->withPivot('is_manager');
    }

    public function managedFridges(){
        return $this->getUser()->fridges()->wherePivot('is_manager', true);
    }

    public function isFridgeManager(Fridge $fridge){
        return $fridge->managers->contains('id', $this->getUser()->id);
    }

    public function isFridgeUser(Fridge $fridge){
        return $this->getUser()->fridges->contains('id', $fridge->id);
    }

    public function isFridgeUserNoOwner(Fridge $fridge){
        return $this->getUser()->fridges->contains('id', $fridge->id) && !$this->isFridgeOwner($fridge);
    }

    public function isActualRank($role){
        return $this->getUser()->role == $role;
    }

    public function isFridgeOwner(Fridge $fridge){
        return $fridge->owner_id == $this->id;
    }

    public function ownFridges(){
        return $this->getUser()->hasMany(Fridge::class, 'owner_id');
    }

    public function isPermittedToManage(Fridge $fridge){
        return $this->getUser()->isFridgeManager($fridge) || $this->getUser()->isFridgeOwner($fridge);
    }
}

?>
