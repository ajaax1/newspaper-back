<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IndustrialGuideService;
use App\Models\IndustrialGuide;
use App\Http\Requests\StoreIndustrialGuideResquest;
use App\Http\Requests\UpdateIndustrialGuideRequest;

class IndustrialGuideController extends Controller
{
    protected $service;

    public function __construct(IndustrialGuideService $service)
    {
        $this->service = $service;
    }

    public function index($search)
    {
        if ($search === 'null') {
            $search = null;
        }

        return response()->json($this->service->getAll($search));
    }

    public function store(StoreIndustrialGuideResquest $request)
    {
        $data = $request->validated();
        $newGuide = $this->service->create($data);
        return response()->json($newGuide, 201);
    }

    public function show($slug)
    {
        $guide = $this->service->findBySlug($slug);

        if (!$guide) {
            return response()->json([], 200);
        }

        return response()->json($guide);
    }

    public function update(UpdateIndustrialGuideRequest $request, $id)
    {
        $guide = IndustrialGuide::find($id);

        if (!$guide) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validated();

        $this->service->update($guide, $data);
        return response()->json(['message' => 'Atualizado com sucesso']);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deletado com sucesso']);
    }

    public function industrialGuideSector($sectorName, $search)
    {
        if ($search === 'null') {
            $search = null;
        }
        if ($sectorName === 'null') {
            $sectorName = null;
        }

        $guides = $this->service->industrialGuideSector($sectorName, $search);

        return $guides;
    }
}
