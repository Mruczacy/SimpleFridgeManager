<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Fridge extends Model {

        use HasFactory, Traits\FridgeUtils;

        public $timestamps= false;

        protected $fillable = [
            'name',
            'owner_id',
        ];
        public function getFridge() : Fridge {
            return $this;
        }

    }

?>
