<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-30 days', 'now'); // data aleatória

        // Criação da imagem
        $imageName = 'news_' . time() . '_' . rand(1000, 9999) . '.jpg';
        $imagePath = storage_path('app/public/news_images/' . $imageName);

        $im = imagecreatetruecolor(800, 600);
        $bg = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefill($im, 0, 0, $bg);
        imagejpeg($im, $imagePath, 80);
        imagedestroy($im);

        return [
            'title'        => $this->faker->sentence(6),
            'sub_title'    => $this->faker->sentence(10),
            'content'      => $this->faker->paragraph(5),
            'image_url'    => 'news_images/' . $imageName,
            'badge'        => $this->faker->word(),
            'user_id'      => 1,
            'top_position' => null,
            'status'       => 'published',
            'slug'         => $this->faker->slug(),
            'hours'        => $this->faker->time('H:i:s'),
            'created_at'   => $createdAt,
            'updated_at'   => $createdAt,
        ];
    }
}
