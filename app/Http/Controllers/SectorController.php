<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use App\Services\SectorService;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class SectorController extends Controller
{
    protected $sectorService;

    public function __construct(SectorService $sectorService)
    {
        $this->sectorService = $sectorService;
    }

    public function index()
    {
        return $this->sectorService->getAll();
    }

    public function panel()
    {
        return $this->sectorService->getAllPanel();
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O campo nome deve ser uma string.',
                'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            ]
        );

        $sector = $this->sectorService->create($validated);

        return response()->json($sector, 201);
    }

    public function show(int $id)
    {
        $sector = $this->sectorService->find($id);
        return $sector;
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sector = Sector::find($id);
        if (!$sector) {
            return response()->json(['message' => 'Setor não encontrado'], 200);
        }

        return $sector = $this->sectorService->update($sector, $validated);
    }

    public function destroy(int $id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return response()->json(['message' => 'Setor não encontrado.'], 200);
        }

        return $this->sectorService->delete($sector);
    }


}
