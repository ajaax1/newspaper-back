<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllPanel()
    {
        $categories = Category::paginate(10);
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], 404);
        }
        return $categories;
    }

    public function getAll()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], 404);
        }
        return $categories;
    }

    public function create(array $data)
    {
        try {
            return Category::create($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha ao criar categoria.'], 400);
        }
    }

    public function find(int $id)
    {
        try {
            return Category::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }
    }

    public function update(Category $category, array $data)
    {
        if ($category->update($data)) {
            return response()->json(['message' => 'Categoria atualizada com sucesso.']);
        } else {
            return response()->json(['message' => 'Falha ao atualizar categoria.'], 400);
        }
    }

    public function delete(Category $category)
    {
        if ($category->delete()) {
            return response()->json(['message' => 'Categoria excluída com sucesso.']);
        } else {
            return response()->json(['message' => 'Falha ao excluir categoria.'], 400);
        }
    }
}
