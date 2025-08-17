<?php

namespace App\Services;

use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\search;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function getAll()
    {
        $posicoesEsperadas = ['main_top', 'top_1', 'top_2', 'top_3'];

        // Notícias principais
        $noticiasTop = News::whereIn('top_position', $posicoesEsperadas)
            ->where('status', 'published')
            ->orderByRaw("FIELD(top_position, 'main_top', 'top_1', 'top_2', 'top_3')")
            ->get()
            ->keyBy('top_position');

        // Últimas notícias que não estão nas principais
        $ultimasNoticias = News::where('status', 'published')
            ->whereNotIn('id', $noticiasTop->pluck('id'))
            ->orderByDesc('created_at')
            ->get();

        // Monta as notícias principais, preenchendo posições vazias
        $principais = collect($posicoesEsperadas)->map(function ($posicao) use (&$noticiasTop, &$ultimasNoticias) {
            return $noticiasTop->get($posicao) ?? $ultimasNoticias->shift();
        })->filter();

        $idsPrincipais = $principais->pluck('id')->toArray();

        // Pega categorias com notícias e banners
        $editorias = Category::with([
            'news' => function ($query) use ($idsPrincipais) {
                $query->where('status', 'published')
                    ->whereNotIn('news.id', $idsPrincipais)
                    ->orderByDesc('created_at'); // removido take(4) aqui
            },
            'banners.bannerImages'
        ])->get();

        // Transformar notícias e banners em array final
        $editorias = $editorias->map(function ($categoria) {
            $data = $categoria->toArray();

            // Garante 4 notícias por categoria
            $data['news'] = collect($categoria->news)->take(3)->values();

            // Banner images
            $bannerImages = [];
            foreach ($categoria->banners as $banner) {
                foreach ($banner->bannerImages as $image) {
                    if ($image->image_url) {
                        $bannerImages[] = $image->image_url;
                    }
                }
            }
            $data['banners'] = $bannerImages;

            return $data;
        });

        return response()->json([
            'principais' => $principais,
            'editorias' => $editorias,
        ]);
    }




    public function getAllPanel($categoryId, $search)
    {
        $query = News::with(['user', 'categories'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sub_title', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $news = $query->paginate(10);

        return response()->json($news);
    }



    public function create(array $data)
    {

        try {
            return DB::transaction(function () use ($data) {
                $slug = Str::slug($data['title']);

                $count = 0;
                $baseSlug = $slug;

                while (News::where('slug', $slug)->exists()) {
                    $count++;
                    $slug = $baseSlug . '-' . $count;
                }

                $data['slug'] = $slug;
                $data['user_id'] = auth()->id();
                $data['hours'] = now()->format('H:i:s');

                $news = News::create($data);

                if (!empty($data['category_ids'])) {
                    $news->categories()->sync($data['category_ids']);
                }

                return $news->load('categories');
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar notícia',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function find(string $slug)
    {
        try {
            return News::with(['user', 'categories'])
                ->where('slug', $slug)
                ->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Notícia não encontrada.'], 404);
        }
    }

    public function update($id, array $data)
    {
        try {
            $news = News::find($id);
            if (!$news) {
                return response()->json(['message' => 'Notícia não encontrada.'], 404);
            }
            return DB::transaction(function () use ($news, $data) {
                $data['user_id'] = auth()->id();
                if (isset($data['status']) && $data['status'] === 'published' && $news->status !== 'published') {
                    $data['hours'] = now()->format('H:i:s');
                }
                $updated = $news->update($data);
                if (isset($data['category_ids'])) {
                    $news->categories()->sync($data['category_ids']);
                }

                if ($updated) {
                    return response()->json([
                        'message' => 'Notícia atualizada com sucesso.',
                        'news' => $news->load('categories')
                    ]);
                } else {
                    return response()->json(['message' => 'Falha ao atualizar notícia.'], 400);
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar notícia.'], 500);
        }
    }

    public function delete(News $news)
    {
        if ($news->getRawOriginal('image_url')) {
            Storage::disk('public')->delete($news->getRawOriginal('image_url'));
        }

        // Agora deleta do banco
        if ($news->delete()) {
            return response()->json(['message' => 'Notícia deletada com sucesso.'], 200);
        } else {
            return response()->json(['message' => 'Falha ao deletar notícia.'], 400);
        }
    }

    public function newsCategory($categoryName, $search = null)
    {
        try {
            $perPage = 10;

            $news = News::with(['user', 'categories'])
                ->whereHas('categories', function ($query) use ($categoryName) {
                    $query->where('categories.name', $categoryName);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json($news);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar notícias.'], 500);
        }
    }
}
