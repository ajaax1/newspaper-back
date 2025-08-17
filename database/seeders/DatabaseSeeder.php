<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            NewsCategorySeeder::class, // cria 20 news e associa categorias

        ]);
    }
}
