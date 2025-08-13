<?php

namespace App\Services;

use App\Models\SocialColumn;
use App\Models\SocialColumnImage;
use Illuminate\Support\Facades\Storage;

class SocialColumnService
{
    public function getAll($search)
    {
        $columns = SocialColumn::with('images');

        if ($search) {
            $columns->where('name', 'like', "%{$search}%");
        }

        return response()->json(
            $columns->paginate(10) 
        );
    }

    public function create(array $data)
    {
        $column = SocialColumn::create($data);
        return response()->json($column, 201);
    }

    public function createImage(array $data)
    {
        // Upload da imagem
        if (isset($data['image_url']) && $data['image_url']->isValid()) {
            $path = $data['image_url']->store('social_columns', 'public');
            $data['image_url'] = $path;
        }

        $image = SocialColumnImage::create($data);
        return response()->json($image, 201);
    }

    public function find($id)
    {
        $column = SocialColumn::with('images')->find($id);

        if (!$column) {
            return response()->json(['message' => 'Coluna não encontrada.'], 404);
        }

        return response()->json($column);
    }
}
