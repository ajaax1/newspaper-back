<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SocialColumnService;
use App\Models\SocialColumnImage;
use Illuminate\Support\Facades\Storage;

class SocialColumnController extends Controller
{
    protected $socialColumnService;

    public function __construct(SocialColumnService $socialColumnService)
    {
        $this->socialColumnService = $socialColumnService;
    }

    public function index($search)
    {
        if ($search == 'null') {
            $search = null;
        }
        return $this->socialColumnService->getAll($search);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'images' => 'required|array',
                'images.*.images.*.image_url' => 'nullable|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff,tif,ico,heic,heif|max:max:2048',
                'images.*.is_cover' => 'boolean',
                'is_cover'    => 'nullable|array',
                'is_cover.*'  => 'boolean',
            ]
        );

        return $this->socialColumnService->create($data);
    }


    public function destroyImage($id)
    {
        $image = SocialColumnImage::find($id);

        if (!$image) {
            return response()->json(['message' => 'Imagem não encontrada.'], 404);
        }

        $imagePath = $image->getRawOriginal('image_url');

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $image->delete();

        return response()->json(['message' => 'Imagem excluída com sucesso.'], 200);
    }

    public function show($slug)
    {
        return $this->socialColumnService->find($slug);
    }

    public function destroy($id)
    {
        return $this->socialColumnService->destroy($id);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'images' => 'sometimes|required|array',
                'images.*.image_url' => 'sometimes|mimes:jpeg,png,jpg,gif,webp,bmp,svg,tiff,tif,ico,heic,heif|max:max:2048',
                'images.*.is_cover' => 'boolean',
                'is_cover'    => 'nullable|array',
                'is_cover.*'  => 'boolean',
            ]
        );

        return $this->socialColumnService->update($id, $data);
    }
}
