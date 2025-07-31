<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return $this->categoryService->getAll();
    }
    public function panel()
    {
        return $this->categoryService->getAllPanel();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ],
        [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',
        ]);
        $category = $this->categoryService->create($validated);
        return response()->json($category, 201);
    }

    public function show(int $id)
    {
        $category = $this->categoryService->find($id);
        return $category;
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $category = Category::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        return $category = $this->categoryService->update($category, $validated);
    }

    public function destroy(int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }
        return $this->categoryService->delete($category);
    }
}
