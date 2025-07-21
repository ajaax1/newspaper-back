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

    public function destroy(News $news)
    {
        $this->newsService->delete($news);
        return response()->json(null, 204);
    }
}
