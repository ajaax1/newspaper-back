<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialColumn extends Model
{
    use HasFactory;

    protected $fillable = [
       'title',
        'slug',
        'hours',
        'user_id',
        'description'
    ];

    /**
     * Imagens relacionadas à coluna social.
     */
    public function images()
    {
        return $this->hasMany(SocialColumnImage::class);
    }

    /**
     * Imagem de capa da coluna social.
     */
    public function coverImage()
    {
        return $this->hasOne(SocialColumnImage::class)->where('is_cover', true);
    }

    /**
     * Usuário que criou a coluna social.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
