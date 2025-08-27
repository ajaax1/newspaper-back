<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class MagazineImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'magazine_id',
        'image_url',
    ];

    /**
     * Revista a que essa imagem pertence.
     */
    public function magazine()
    {
        return $this->belongsTo(Magazine::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_object($value) && method_exists($value, 'store')) {
                    return $value->store('magazine_images', 'public');
                }
                return $value;
            },
            get: fn($value) => $value ? Storage::url($value) : null,
        );
    }
}
