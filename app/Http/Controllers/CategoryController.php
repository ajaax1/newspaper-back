<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\BannerController;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

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

        $category = $this->categoryService->create($validated);

        Banner::create([
            'name' => $validated['name'],
            'category_id' => $category->id,
        ]);

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
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada'], 200);
        }

        $banners = Banner::where('category_id', $category->id)->get();
        if (!$banners->isEmpty()) {
            Banner::where('category_id', $category->id)->update(['name' => $validated['name']]);
        }

        return $category = $this->categoryService->update($category, $validated);
    }


    public function destroy(int $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        $banners = Banner::where('category_id', $category->id)->get();

        foreach ($banners as $banner) {
            $images = \App\Models\BannerImages::where('banner_id', $banner->id)->get();

            foreach ($images as $image) {
                if ($image->image_url) {
                    $path = str_replace('/storage/', '', $image->image_url);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
                $image->delete();
            }
            $banner->delete();
        }

        return $this->categoryService->delete($category);
    }
}
