<?php

namespace App\Services;
use App\Models\Magazine;
use Illuminate\Support\Facades\Storage;

class MagazineService
{
    public function find($id)
    {
        $magazine = Magazine::find($id);
        if($magazine == null){
            return response()->json(['message'=>'Revista não encontrada']);
        }
        return $magazine;
    }

    public function create(array $data)
    {
        return Magazine::create($data);
    }

    public function update($id, array $data)
    {

        $magazine = Magazine::find($id);
        if($magazine == null){
            return response()->json(['message'=>'Revista não encontrada']);
        }

        if (isset($data['file'])) {
            $oldFile = $magazine->getRawOriginal('file');
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $magazine->update($data);
        return $magazine;
    }

    public function delete($id)
    {

        $magazine = Magazine::find($id);
        if($magazine == null){
            return response()->json(['message'=>'Revista não encontrada']);
        }

        $oldFile = $magazine->getRawOriginal('file');
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }
        $magazine->delete();
        return response()->json(['message'=>'Deletado com sucesso']);
    }
}
