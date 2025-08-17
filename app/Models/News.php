<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'content',
        'image_url',
        'badge',
        'user_id',
        'top_position',
        'status',
        'slug',
        'hours'
    ];

    // Relacionamento com o autor da notícia
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com categorias (muitos para muitos)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_news');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_object($value) && method_exists($value, 'store')) {
                    return $value->store('news_images', 'public');
                }
                return $value;
            },
            get: fn($value) => $value ? Storage::url($value) : null,
        );
    }
}
