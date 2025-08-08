<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BannerImages;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted()
    {
        static::deleting(function ($banner) {
            // Garante que a relação esteja carregada
            $banner->load('bannerImages');

            foreach ($banner->bannerImages as $image) {
                // Deleta o arquivo do disco
                if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }

                // Deleta o registro do banco
                $image->delete();
            }
        });
    }
}
