<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        // Gera 20 notícias fake
        News::factory(20)->create();
    }
}
