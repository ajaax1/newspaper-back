<?php

namespace App\Http\Controllers;

use App\Models\IndustrialGuide;
use App\Models\SocialColumn;
use App\Models\Magazine;
use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getAll()
    {
        try {
            $posicoesEsperadas = ['main_top', 'top_1', 'top_2', 'top_3'];

            // Notícias com posições prioritárias
            $noticiasTop = News::whereIn('top_position', $posicoesEsperadas)
                ->where('status', 'published')
                ->orderByRaw("FIELD(top_position, 'main_top', 'top_1', 'top_2', 'top_3')")
                ->get()
                ->keyBy('top_position');

            // Últimas notícias publicadas (excluindo as já selecionadas)
            $ultimasNoticias = News::where('status', 'published')
                ->whereNotIn('id', $noticiasTop->pluck('id'))
                ->orderByDesc('created_at')
                ->get();

            // Monta as principais, preenchendo posições vazias
            $principaisNoticias = collect($posicoesEsperadas)->map(function ($posicao) use (&$noticiasTop, &$ultimasNoticias) {
                return $noticiasTop->get($posicao) ?? $ultimasNoticias->shift();
            })->filter();

            // Outras seções
            $socialColumns = SocialColumn::with('images')
                ->orderByDesc('created_at')
                ->take(3)
                ->get();

            $magazines = Magazine::orderByDesc('created_at')
                ->take(3)
                ->get();

            $industrialGuides = IndustrialGuide::with('sectors')->orderByDesc('created_at')
                ->take(3)
                ->get();

            return response()->json([
                'principais_noticias' => $principaisNoticias->values(), // para manter como array indexado
                'social_columns' => $socialColumns,
                'magazines' => $magazines,
                'industrial_guides' => $industrialGuides,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar os dados.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
