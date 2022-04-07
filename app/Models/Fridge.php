<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Fridge extends Model {

        use HasFactory;

        public $timestamps= false;

        protected $fillable = [
            'name',
            'owner_id',
        ];

        public function products()
        {
            return $this->hasMany(Product::class);
        }


        public function users(){
            return $this->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id')->withPivot('is_manager');
        }

        public function managers(){
            return $this->users()->wherePivot('is_manager', true);
        }

        public function owner(){
            return $this->belongsTo(User::class, 'owner_id');
        }
    }

?>
