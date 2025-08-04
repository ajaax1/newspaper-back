<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BannerImages;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id'
    ];

    public function bannerImages()
    {
        return $this->hasMany(BannerImages::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
