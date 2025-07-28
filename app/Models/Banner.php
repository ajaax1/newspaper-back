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
    ];

    public function bannerImages()
    {
        return $this->hasMany(BannerImages::class);
    }
}
