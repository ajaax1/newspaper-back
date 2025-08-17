<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Magazine extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'file',
        'title',
        'user_id',
        'description',
        'image_url'
    ];

    protected function file(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_object($value) && method_exists($value, 'store')) {
                    return $value->store('magazine_files', 'public');
                }
                return $value;
            },
            get: fn($value) => $value ? Storage::url($value) : null,
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_object($value) && method_exists($value, 'store')) {
                    return $value->store('magazines_images', 'public');
                }
                return $value;
            },
            get: fn($value) => $value ? Storage::url($value) : null,
        );
    }

    /**
     * A magazine belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
