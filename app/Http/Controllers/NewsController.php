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

    public function panel($search, $category)
    {
        if ($search === 'null') $search = null;
        if ($category === 'null') $category = null;
        return $this->newsService->getAllPanel($search, $category);
    }

    public function store(StoreNewsRequest $request)
    {
        $validated = $request->validated();
        $news = $this->newsService->create($validated);
        return response()->json($news, 201);
    }

    public function show($id)
    {
        $news = $this->newsService->find($id);
        return response()->json($news);
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $validated = $request->validated();
        $news = $this->newsService->update($news, $validated);
        return response()->json($news);
    }

    public function destroy(int $id)
    {
        $news = $this->newsService->find($id);
        if (!$news) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $this->newsService->delete($news);
        return response()->json(null, 204);
    }
}
