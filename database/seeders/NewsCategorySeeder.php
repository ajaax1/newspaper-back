<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Category;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Pega todas as categorias fixas
        $categories = Category::whereIn('name', ['Tecnologia', 'Cultura', 'Saúde', 'Meio Ambiente'])->get();

        // Gera 20 notícias
        $newsItems = News::factory(20)->create();

        // Distribui categorias entre as notícias
        foreach ($newsItems as $index => $news) {
            // Pega uma categoria de forma cíclica
            $category = $categories[$index % $categories->count()];

            // Associa a categoria à notícia
            $news->categories()->attach($category->id);
        }
    }
}
