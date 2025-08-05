<?php

namespace App\Http\Controllers;

use App\Models\BusinessColumn;
use Illuminate\Http\Request;
use App\Services\BusinessColumnService;

class BusinessColumnController extends Controller
{
    protected $service;

    public function __construct(BusinessColumnService $service)
    {
        $this->service = $service;
    }

    public function index($search)
    {
        if($search == 'null'){
            $search = null;
        }
        return response()->json($this->service->getAll($search));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image_url' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'address' => 'nullable|string',
            'number' => 'nullable|string',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'image_url.required' => 'A imagem é obrigatória.',
            'image_url.file' => 'A imagem deve ser um arquivo válido.',
            'image_url.mimes' => 'A imagem deve estar no formato: jpg, jpeg, png ou webp.',
            'image_url.max' => 'A imagem não pode ter mais de 2MB.',

            'address.string' => 'O endereço deve ser um texto.',
            'number.string' => 'O número deve ser um texto.',
            'description.string' => 'A descrição deve ser um texto.',
        ]);


        $newColumn = $this->service->create($data);
        return response()->json($newColumn, 201);
    }

    public function show($id)
    {
        $column = $this->service->findById($id);

        if (!$column) {
            return response()->json([], 200);
        }

        return response()->json($column);
    }

    public function update(Request $request, $id)
    {
        $column = $this->service->findById($id);

        if (!$column) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'image_url' => 'nullable|string',
            'address' => 'sometimes|string',
            'number' => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $updated = $this->service->update($column, $data);
        return response()->json(['message' => 'Atualizado com sucesso']);
    }

    public function destroy($id)
    {
        $column = $this->service->findById($id);

        if (!$column) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $this->service->delete($column);
        return response()->json(['message' => 'Deletado com sucesso']);
    }
}
