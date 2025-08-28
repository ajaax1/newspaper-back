<?php

namespace App\Http\Controllers;

use App\Models\IndustrialGuide;
use App\Models\SocialColumn;
use App\Models\Magazine;
use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Banner;

class HomeController extends Controller
{
    public function getAll()
    {
        try {
            $posicoesEsperadas = ['main_top', 'top_1', 'top_2', 'top_3'];
            $bannerNames = ['home 1', 'home 2', 'home 3'];

            // Busca banners home
            $bannersHome = Banner::with('bannerImages')
                ->whereIn('name', $bannerNames)
                ->get()
                ->mapWithKeys(function ($banner) {
                    $images = $banner->bannerImages->pluck('image_url')->filter()->values();
                    // transforma o nome para snake_case para a chave
                    $key = strtolower(str_replace(' ', '_', $banner->name));
                    return [$key => $images];
                });

            // Notícias principais
            $noticiasTop = News::whereIn('top_position', $posicoesEsperadas)
                ->where('status', 'published')
                ->orderByRaw("FIELD(top_position, 'main_top', 'top_1', 'top_2', 'top_3')")
                ->get()
                ->keyBy('top_position');

            $ultimasNoticias = News::where('status', 'published')
                ->whereNotIn('id', $noticiasTop->pluck('id'))
                ->orderByDesc('created_at')
                ->get();

            $principaisNoticias = collect($posicoesEsperadas)->map(function ($posicao) use (&$noticiasTop, &$ultimasNoticias) {
                return $noticiasTop->get($posicao) ?? $ultimasNoticias->shift();
            })->filter();

            // Outras seções
            $socialColumns = SocialColumn::with('images')->orderByDesc('created_at')->take(3)->get();
            $magazines = Magazine::orderByDesc('created_at')->take(3)->get();
            $industrialGuides = IndustrialGuide::with('sectors')->orderByDesc('created_at')->take(3)->get();

            return response()->json([
                'principais_noticias' => $principaisNoticias->values(),
                'social_columns' => $socialColumns,
                'magazines' => $magazines,
                'industrial_guides' => $industrialGuides,
                'banners_home' => [
                    'home_1' => $bannersHome['home_1'] ?? [],
                    'home_2' => $bannersHome['home_2'] ?? [],
                    'home_3' => $bannersHome['home_3'] ?? [],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar os dados.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
