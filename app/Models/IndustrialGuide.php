<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class IndustrialGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'address',
        'number',
        'description',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_object($value) && method_exists($value, 'store')) {
                    return $value->store('guide_images', 'public');
                }
                return $value;
            },
            get: fn($value) => $value ? Storage::url($value) : null,
        );
    }
}
