<?php

namespace App\Services;

use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

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


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
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
            $news->update($data);
            if (isset($data['category_ids'])) {
                $news->categories()->sync($data['category_ids']);
            }

            return $news->load('categories');
        });
    }

    public function delete(News $news)
    {
        return $news->delete();
    }
}
