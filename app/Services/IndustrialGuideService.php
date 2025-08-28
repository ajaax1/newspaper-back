<?php

namespace App\Services;

use App\Models\IndustrialGuide;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Sector;


class IndustrialGuideService
{
    public function getAll($search)
    {
        $query = IndustrialGuide::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $query->with(['sectors', 'user'])->orderBy('created_at', 'desc');

        return $query->paginate(10);
    }

    public function findBySlug(string $slug)
    {
        $guide = IndustrialGuide::with(['sectors', 'user'])->where('slug', $slug)->first();

        if (!$guide) {
            return response()->json(['message' => 'Guia não encontrado.'], 404);
        }

        return $guide;
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $slug = Str::slug($data['name']);
                $count = 0;
                $baseSlug = $slug;

                while (IndustrialGuide::where('slug', $slug)->exists()) {
                    $count++;
                    $slug = $baseSlug . '-' . $count;
                }

                $data['slug'] = $slug;
                $data['user_id'] = auth('sanctum')->id(); // <- pega do token

                if (!$data['user_id']) {
                    throw new \Exception('Usuário não autenticado.');
                }

                $sectorIds = $data['sector_ids'] ?? [];
                unset($data['sector_ids']);

                $guide = IndustrialGuide::create($data);

                if (!empty($sectorIds)) {
                    $guide->sectors()->sync($sectorIds);
                }

                return $guide->load('sectors');
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar guia industrial',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(IndustrialGuide $industrialGuide, array $data)
    {
        try {
            return DB::transaction(function () use ($industrialGuide, $data) {

                // Atualiza slug se necessário
                if (!empty($data['name']) && $data['name'] !== $industrialGuide->name) {
                    $slug = Str::slug($data['name']);
                    $count = 0;
                    $baseSlug = $slug;

                    while (
                        IndustrialGuide::where('slug', $slug)
                        ->where('id', '!=', $industrialGuide->id)
                        ->exists()
                    ) {
                        $count++;
                        $slug = $baseSlug . '-' . $count;
                    }

                    $data['slug'] = $slug;
                }

                $data['user_id'] = auth('sanctum')->id();
                if (!$data['user_id']) {
                    throw new \Exception('Usuário não autenticado.');
                }

                $sectorIds = $data['sector_ids'] ?? null;
                unset($data['sector_ids']);

                // 🔹 Se houver nova imagem
                if (isset($data['image_url']) && $data['image_url'] instanceof \Illuminate\Http\UploadedFile) {

                    // Corrige path antigo removendo "/storage/" se existir
                    $oldImage = $industrialGuide->image_url;
                    $oldImage = preg_replace('#^/storage/#', '', $oldImage);

                    // Deleta arquivo antigo se existir
                    if ($oldImage && \Storage::disk('public')->exists($oldImage)) {
                        \Storage::disk('public')->delete($oldImage);
                    }

                    // Salva a nova imagem
                    $data['image_url'] = $data['image_url']->store('guide_images', 'public');
                }

                $industrialGuide->update($data);

                if (is_array($sectorIds)) {
                    $industrialGuide->sectors()->sync($sectorIds);
                }

                return $industrialGuide->load('sectors');
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar guia industrial',
                'error'   => $e->getMessage()
            ], 500);
        }
    }







    public function delete($id)
    {
        $industrialGuide = IndustrialGuide::find($id);

        if (!$industrialGuide) {
            throw new \Exception("IndustrialGuide não encontrado");
        }

        $path = $industrialGuide->getRawOriginal('image_url');

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        return $industrialGuide->delete();
    }



    public function industrialGuideSector($sectorName = null, $search = null)
    {
        try {
            $perPage = 10;

            $guides = IndustrialGuide::with(['user', 'sectors'])
                ->when($sectorName, function ($query) use ($sectorName) {
                    $query->whereHas('sectors', function ($q) use ($sectorName) {
                        $q->where('sectors.name', $sectorName);
                    });
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json($guides);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar guias industriais.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
