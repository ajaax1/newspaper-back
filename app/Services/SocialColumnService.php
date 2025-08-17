<?php

namespace App\Services;

use App\Models\SocialColumn;
use App\Models\SocialColumnImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SocialColumnService
{
    public function getAll($search)
    {
        $columns = SocialColumn::with('images');

        if ($search) {
            $columns->where('title', 'like', "%{$search}%");
        }

        return response()->json(
            $columns->paginate(10)
        );
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // cria slug único
            $slug = Str::slug($data['title']);
            $count = 0;
            $baseSlug = $slug;
            while (SocialColumn::where('slug', $slug)->exists()) {
                $count++;
                $slug = $baseSlug . '-' . $count;
            }

            // cria coluna
            $column = SocialColumn::create([
                'title'       => $data['title'],
                'description' => $data['description'],
                'slug'        => $slug,
                'user_id'     => auth()->id(),
                'hours'       => now()->format('H:i:s'),
            ]);

            // cria imagens
            foreach ($data['images'] as $index => $file) {
                $path = $file->store('social_columns', 'public');

                SocialColumnImage::create([
                    'social_column_id' => $column->id,
                    'image_url'        => $path,
                    'is_cover'         => isset($data['is_cover'][$index]) && $data['is_cover'][$index] == 1,
                ]);
            }

            return $column->load('images');
        });
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

    public function find($slug)
    {
        $column = SocialColumn::with('images')->where('slug', $slug)->first();

        if (!$column) {
            return response()->json(['message' => 'Coluna não encontrada.'], 404);
        }

        return response()->json($column);
    }


    public function destroy($id)
    {
        $column = SocialColumn::find($id);

        if (!$column) {
            return response()->json(['message' => 'Coluna não encontrada.'], 404);
        }

        // Remove as imagens associadas
        foreach ($column->images as $image) {
            $imagePath = $image->getRawOriginal('image_url');
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $image->delete();
        }

        $column->delete();

        return response()->json(['message' => 'Coluna excluída com sucesso.'], 200);
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $column = SocialColumn::find($id);

            if (!$column) {
                return response()->json(['message' => 'Coluna não encontrada.'], 404);
            }

            // Atualiza slug se o título mudou
            if (isset($data['title']) && $data['title'] !== $column->title) {
                $slug = Str::slug($data['title']);
                $count = 0;
                $baseSlug = $slug;
                while (SocialColumn::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $count++;
                    $slug = $baseSlug . '-' . $count;
                }
                $data['slug'] = $slug;
            }

            // Atualiza dados principais
            $column->update([
                'title'       => $data['title'] ?? $column->title,
                'description' => $data['description'] ?? $column->description,
                'slug'        => $data['slug'] ?? $column->slug,
                'user_id'     => auth()->id(),
                'hours'       => now()->format('H:i:s'),
            ]);

            // Se enviou imagens, adiciona sem deletar as antigas
            if (isset($data['images'])) {
                foreach ($data['images'] as $index => $file) {
                    $path = $file->store('social_columns', 'public');

                    SocialColumnImage::create([
                        'social_column_id' => $column->id,
                        'image_url'        => $path,
                        'is_cover'         => isset($data['is_cover'][$index]) && $data['is_cover'][$index] == 1,
                    ]);
                }
            }

            return $column->load('images');
        });
    }
}
