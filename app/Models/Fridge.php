<?php
    namespace App\Models;

    use App\Models\Product;
    use App\Models\User;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Fridge extends Model {

        use HasFactory;

        public $timestamps= false;

        protected $fillable = [
            'name',
            'owner_id',
        ];

        public function products() : HasMany {
            return $this->hasMany(Product::class)->orderBy('expiration_date', 'asc');
        }

        public function users() : BelongsToMany {
            return $this->belongsToMany(User::class, 'fridgesToUsers', 'fridge_id', 'user_id')->withPivot('is_manager');
        }

        public function managers() : BelongsToMany {
            return $this->users()->wherePivot('is_manager', true);
        }

        public function owner() : BelongsTo{
            return $this->belongsTo(User::class, 'owner_id');
        }

    }

?>
