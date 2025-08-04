<?php

namespace App\Models;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->news()->detach();
        });
    }


    public function news()
    {
        return $this->belongsToMany(News::class, 'categories_news');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}
