<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $appends = ['image_url'];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

     /**
     * Accessor untuk mendapatkan URL Gambar
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->image) {
                    return asset('storage/' . $this->image);
                }

                return asset('assets/images/no-image.png');
            },
        );
    }
}
