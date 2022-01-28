<?php
    namespace App\Models;

    class Fridge {
        protected $fillable = [
            'name',
        ];

        public function products()
        {
            return $this->hasMany(Product::class);
        }


        public function users(){
            return $this->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id');
        }
    }

?>
