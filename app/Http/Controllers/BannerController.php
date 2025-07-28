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

}
