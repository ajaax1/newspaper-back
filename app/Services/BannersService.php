<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\BannerImages;
class BannersService
{
    public function getAll()
    {
        $banners = Banner::with(['bannerImages'])->get();
        return response()->json($banners);
    }

    public function create(array $data)
    {
        $banner = BannerImages::create([
            'banner_id' => $data['banner_id'],
            'image_url' => $data['image_url'],
        ]);
        return response()->json($banner, 201);
    }
}
