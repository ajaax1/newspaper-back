<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Services\BannersService;
use App\Models\BannerImages;
use Illuminate\Support\Facades\Storage;


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
                'banner_id' => 'required|exists:banners,id',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'banner_id.exists' => 'O banner selecionado é inválido.',
                'image_url.required' => 'A imagem é obrigatória.',
                'image_url.image' => 'O arquivo enviado deve ser uma imagem.',
                'image_url.mimes' => 'A imagem deve estar nos formatos: jpeg, png, jpg ou gif.',
                'image_url.max' => 'A imagem não pode ter mais de 2MB.',
            ]
        );

        $banner = $this->bannersService->create($data);
        return response()->json($banner, 201);
    }

    public function destroy($id)
    {
        $bannerImage = BannerImages::find($id);

        if (!$bannerImage) {
            return response()->json(['message' => 'Imagem do banner não encontrada.'], 404);
        }

        $imagePath = $bannerImage->getRawOriginal('image_url');

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $bannerImage->delete();

        return response()->json(['message' => 'Imagem do banner excluída com sucesso.'], 200);
    }
}
