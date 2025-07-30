<?php

namespace App\Services;

use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\search;

class NewsService
{
    public function getAll()
    {
        $posicoesEsperadas = ['main_top', 'top_1', 'top_2', 'top_3'];

        $noticiasTop = News::whereIn('top_position', $posicoesEsperadas)
            ->where('status', 'published')
            ->orderByRaw("FIELD(top_position, 'main_top', 'top_1', 'top_2', 'top_3')")
            ->get()
            ->keyBy('top_position');

        $ultimasNoticias = News::where('status', 'published')
            ->whereNotIn('id', $noticiasTop->pluck('id'))
            ->orderByDesc('created_at')
            ->get();

        $principais = collect($posicoesEsperadas)->map(function ($posicao) use (&$noticiasTop, &$ultimasNoticias) {
            return $noticiasTop->get($posicao) ?? $ultimasNoticias->shift();
        })->filter();

        $idsPrincipais = $principais->pluck('id')->toArray();

        $editorias = Category::with(['news' => function ($query) use ($idsPrincipais) {
            $query->where('status', 'published')
                ->whereNotIn('news.id', $idsPrincipais) // <-- aqui está o ajuste
                ->orderByDesc('created_at')
                ->take(4);
        }])->get();


        return response()->json([
            'principais' => $principais,
            'editorias' => $editorias,
        ]);
    }

    public function getAllPanel($search, $category)
    {
        $query = News::with(['user', 'categories'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sub_title', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        $news = $query->paginate(10); // 10 itens por página

        return response()->json($news);
    }


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = auth()->id();
            $news = News::create($data);

            if (!empty($data['category_ids'])) {
                $news->categories()->sync($data['category_ids']);
            }

            return $news->load('categories');
        });
    }

    public function find(int $id)
    {
        return News::with(['user', 'categories'])->findOrFail($id);
    }

    public function update(News $news, array $data)
    {
        return DB::transaction(function () use ($news, $data) {
            $data['user_id'] = auth()->id();
            $news->update($data);
            if (isset($data['category_ids'])) {
                $news->categories()->sync($data['category_ids']);
            }

            return response()->json($news->toArray());
        });
    }

    public function delete(News $news)
    {
        return $news->delete();
    }
}
