<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
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
