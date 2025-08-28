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
        return $banners;
    }
    public function getTopAndSideImages()
    {
        $banners = Banner::with('bannerImages')
            ->whereIn('id', [1, 2,6])
            ->get();

        // Reduzir o resultado para apenas image_url agrupadas por banner ID
        $result = $banners->mapWithKeys(function ($banner) {
            $imageUrls = $banner->bannerImages->map(fn($img) => $img->image_url)->filter()->values();
            return [$banner->name => $imageUrls];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'banner_id' => 'required|exists:banners,id',
                'image_url' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff,tif,ico,heic,heif|max:5120',
                'link' => 'nullable|url',
            ],
            [
                'banner_id.exists' => 'O banner selecionado é inválido.',
                'image_url.required' => 'A imagem é obrigatória.',
                'image_url.image' => 'O arquivo enviado deve ser uma imagem.',
                'image_url.mimes' => 'A imagem deve estar nos formatos: jpeg, png, jpg ou gif.',
                'image_url.max' => 'A imagem não pode ter mais de 2MB.',
            ]
        );
        return $banner = $this->bannersService->create($data);
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

    public function show(int $id)
    {
        return $this->bannersService->find($id);
    }
}
