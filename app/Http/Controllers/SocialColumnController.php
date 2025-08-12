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

    public function index()
    {
        return $this->socialColumnService->getAll();
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]
        );

        return $this->socialColumnService->create($data);
    }

    public function storeImage(Request $request)
    {
        $data = $request->validate(
            [
                'social_column_id' => 'required|exists:social_columns,id',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_cover' => 'boolean'
            ]
        );

        return $this->socialColumnService->createImage($data);
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

    public function show($id)
    {
        return $this->socialColumnService->find($id);
    }
}
