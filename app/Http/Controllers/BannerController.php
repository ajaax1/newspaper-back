<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Services\BannersService;

class BannerController extends Controller
{
    protected $bannersService;

    public function __construct(BannersService $bannersService)
    {
        $this->bannersService = $bannersService;
    }
    public function index()
    {
        $banners = $this->bannersService->getAll();
        return response()->json($banners);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'title.required' => 'O título é obrigatório.',
                'image_url.required' => 'A imagem é obrigatória.',
                'image_url.image' => 'O arquivo deve ser uma imagem.',
                'image_url.mimes' => 'A imagem deve ser do tipo jpeg, png, jpg ou gif.',
                'image_url.max' => 'A imagem não pode exceder 2MB.',
            ]
        );

        $banner = $this->bannersService->create($data);
        return response()->json($banner, 201);
    }
}
