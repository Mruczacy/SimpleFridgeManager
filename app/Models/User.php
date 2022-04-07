<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Enums\UserRole;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Fridge;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fridges(){
        return $this->belongsToMany(Fridge::class, 'fridgesToUsers', 'user_id', 'fridge_id')->withPivot('is_manager');
    }

    public function managedFridges(){
        return $this->fridges()->wherePivot('is_manager', true);
    }

    public function isFridgeManager(Fridge $fridge){
        return $fridge->managers->contains('id', $this->id);
    }

    public function isFridgeUser(Fridge $fridge){
        return $this->fridges->contains('id', $fridge->id);
    }

    public function isFridgeUserNoOwner(Fridge $fridge){
        return $this->fridges->contains('id', $fridge->id) && !$this->isFridgeOwner($fridge);
    }

    public function isActualRank($role){
        return $this->role == $role;
    }

    public function isFridgeOwner(Fridge $fridge){
        return $fridge->owner_id == $this->id;
    }

    public function ownFridges(){
        return $this->hasMany(Fridge::class, 'owner_id');
    }

    public function isPermittedToManage(Fridge $fridge){
        return $this->isFridgeManager($fridge) || $this->isFridgeOwner($fridge);
    }
}
