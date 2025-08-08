<?php

namespace App\Services;

use App\Models\Sector;

class SectorService
{
    public function getAllPanel()
    {
        return Sector::paginate(10);
    }

    public function getAll()
    {
        return Sector::all();
    }

    public function create(array $data)
    {
        try {
            return Sector::create($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha ao criar setor.'], 400);
        }
    }

    public function find(int $id)
    {
        $sector = Sector::find($id);
        if($sector == null){
            return response()->json(['message' => 'Usuário não encontrado'], 200);
        }
        return $sector;
    }

    public function update(Sector $sector, array $data)
    {
        if ($sector->update($data)) {
            return response()->json(['message' => 'Setor atualizado com sucesso.']);
        } else {
            return response()->json(['message' => 'Falha ao atualizar setor.'], 400);
        }
    }

    public function delete(Sector $sector)
    {
        if ($sector->delete()) {
            return response()->json(['message' => 'Setor excluído com sucesso.']);
        } else {
            return response()->json(['message' => 'Falha ao excluir setor.'], 400);
        }
    }
}
