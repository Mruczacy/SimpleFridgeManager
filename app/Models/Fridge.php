<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Fridge extends Model {

        use HasFactory;

        public $timestamps= false;

        protected $fillable = [
            'name',
        ];

        public function products()
        {
            return $this->hasMany(Product::class);
        }


        public function users(){
            return $this->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id')->withPivot('is_owner');
        }

        public function owners(){
            return $this->users()->wherePivot('is_owner', true);
        }
    }

?>
