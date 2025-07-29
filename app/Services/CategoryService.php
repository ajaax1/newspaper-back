<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllPanel()
    {
        return Category::paginate(10);
    }

    public function getAll()
    {
        return Category::all();
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function find(int $id)
    {
        return Category::findOrFail($id);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
