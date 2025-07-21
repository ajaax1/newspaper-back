<?php

namespace App\Services;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsService
{
    public function getAll()
    {
        return News::with(['user', 'categories'])->get();
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
