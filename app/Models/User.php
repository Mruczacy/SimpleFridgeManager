<?php

namespace App\Models;

use App\Models\Fridge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fridges() : BelongsToMany {
        return $this->belongsToMany(Fridge::class, 'fridgesToUsers', 'user_id', 'fridge_id')->withPivot('is_manager');
    }

    public function managedFridges() : BelongsToMany {
        return $this->fridges()->wherePivot('is_manager', true);
    }

    public function isFridgeManager(Fridge $fridge) : bool {
        return $fridge->managers->contains('id', $this->id);
    }

    public function isFridgeUser(Fridge $fridge) : bool {
        return $this->fridges->contains('id', $fridge->id);
    }

    public function isFridgeUserNoOwner(Fridge $fridge) : bool {
        return $this->fridges->contains('id', $fridge->id) && !$this->isFridgeOwner($fridge);
    }

    public function isActualRank($role) : bool {
        return $this->role == $role;
    }

    public function isFridgeOwner(Fridge $fridge) : bool {
        return $fridge->owner_id == $this->id;
    }

    public function ownFridges() : HasMany {
        return $this->hasMany(Fridge::class, 'owner_id');
    }

    public function isPermittedToManage(Fridge $fridge) : bool {
        return $this->isFridgeManager($fridge) || $this->isFridgeOwner($fridge);
    }

    public function isEqualToAuth() : bool {
        return $this->id == Auth::id();
    }
}
