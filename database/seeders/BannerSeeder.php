<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;
class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Banner::create([
            'name' => 'top',
        ]);

        Banner::create([
            'name' => 'side',
        ]);

        Banner::create([
            'name' => 'home 1',
        ]);

        Banner::create([
            'name' => 'home 2',
        ]);

        Banner::create([
            'name' => 'home 3',
        ]);

        Banner::create([
            'name' => 'pop up',
        ]);
    }
}
