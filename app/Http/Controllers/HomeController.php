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
            $news = News::with(['user', 'categories'])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            $socialColumns = SocialColumn::with('images')->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $magazines = Magazine::orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $industrialGuides = IndustrialGuide::orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return response()->json([
                    'news' => $news,
                    'social_columns' => $socialColumns,
                    'magazines' => $magazines,
                    'industrial_guides' => $industrialGuides,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar os dados.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
