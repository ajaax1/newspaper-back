<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index()
    {
        return $this->newsService->getAll();
    }

    public function panel($categoryId,$search)
    {
        if ($search === 'null') $search = null;
        if ($categoryId === 'null') $categoryId = null;
        return $this->newsService->getAllPanel($categoryId,$search);
    }

    public function store(StoreNewsRequest $request)
    {
        $validated = $request->validated();
        $news = $this->newsService->create($validated);
        return response()->json($news, 201);
    }

    public function show($slug)
    {
        $news = $this->newsService->find($slug);
        return $news;
    }

    public function update(UpdateNewsRequest $request, int $id)
    {
        $validated = $request->validated();

        $news = $this->newsService->update($id, $validated);
        return response()->json(['message' => 'Notícia atualizada com sucesso.'], 200);
    }

    public function destroy(int $id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json(['message' => 'Notícia não encontrada'], 404);
        }

        return $this->newsService->delete($news);
    }

    public function newsCategory($categoryName,$search){
        if($search == 'null'){
            $search = null;
        }
        if($categoryName == 'null'){
            $categoryName = null;
        }
        $news = $this->newsService->newsCategory($categoryName,$search);
        return $news;
    }

    public function relatedNews($categoryName)
    {
        return $this->newsService->relatedNews($categoryName);
    }
}
