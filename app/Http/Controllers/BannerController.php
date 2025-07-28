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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $banner = $this->bannersService->create($data);
        return response()->json($banner, 201);
    }

}
