<?php

namespace App\Models;

class Product {
    protected $fillable = [
        'name',
        'expiration_date',


    ];

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }

}
?>
