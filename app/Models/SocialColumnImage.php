<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialColumnImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_column_id',
        'image_url',
        'is_cover',
    ];

    /**
     * Coluna social a que essa imagem pertence.
     */
    public function socialColumn()
    {
        return $this->belongsTo(SocialColumn::class);
    }
}
