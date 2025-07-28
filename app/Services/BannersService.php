<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
class BannersService
{
    public function getAll()
    {
        $banners = Banner::with(['bannerImages'])->get();
        return response()->json($banners);
    }
}
