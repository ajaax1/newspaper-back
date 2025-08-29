<?php

namespace App\Services;

use App\Models\Magazine;
use Illuminate\Support\Facades\Storage;
use App\Models\MagazineImages;

class MagazineService

{

    public function getAll($search)
    {
        $query = Magazine::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // ordena antes de paginar
        return $query->orderByDesc('created_at')->paginate(10);
    }

    public function findBySlug(string $slug)
    {
        $magazine = Magazine::with(['user'])->where('slug', $slug)->first();

        if ($magazine == null) {
            return ['message' => 'Revista não encontrada'];
        }

        // Buscar as últimas 5 revistas, exceto a atual
        $relatedMagazines = Magazine::with(['user'])
            ->where('id', '!=', $magazine->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return [
            'magazine' => $magazine,
            'related'  => $relatedMagazines
        ];
    }


    public function create(array $data)
    {
        return Magazine::create($data);
    }

    public function update($id, array $data)
    {
        $magazine = Magazine::find($id);
        if (!$magazine) {
            return response()->json(['message' => 'Revista não encontrada'], 404);
        }

        // se usuário mandou novo arquivo substitui
        if (isset($data['file'])) {
            $oldFile = $magazine->getRawOriginal('file');
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        // se usuário mandou nova imagem substitui
        if (isset($data['image_url'])) {
            $oldImage = $magazine->getRawOriginal('image_url');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $magazine->update($data);

        return $magazine;
    }


    public function delete($id)
    {

        $magazine = Magazine::find($id);
        if ($magazine == null) {
            return response()->json(['message' => 'Revista não encontrada']);
        }

        $oldFile = $magazine->getRawOriginal('file');
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        $oldImage = $magazine->getRawOriginal('image_url');
        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }

        $magazine->delete();
        return response()->json(['message' => 'Deletado com sucesso']);
    }
}
