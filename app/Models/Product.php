<?php

namespace App\Models;

class Product {
    protected $fillable = [
        'name',
        'expiration_date',
        'user_id',

    ];

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }
    
}
?>
