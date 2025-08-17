<?php

use Illuminate\Http\Request;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndustrialGuideController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\SocialColumnController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users/{search?}', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::post('news', [NewsController::class, 'store']);
    Route::put('news/{id}', [NewsController::class, 'update']);
    Route::delete('news/{id}', [NewsController::class, 'destroy']);
    Route::get('news/panel/{categoryId?}/{search?}', [NewsController::class, 'panel']);

    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories', [CategoryController::class, 'panel']);

    Route::put('sectors/{id}', [SectorController::class, 'update']);
    Route::delete('sectors/{id}', [SectorController::class, 'destroy']);
    Route::get('sectors/{id}', [SectorController::class, 'show']);
    Route::post('sectors', [SectorController::class, 'store']);
    Route::get('sectors', [SectorController::class, 'panel']);

    Route::get('banners', [BannerController::class, 'index']);
    Route::get('banners/{id}', [BannerController::class, 'show']);
    Route::post('banners', [BannerController::class, 'store']);
    Route::delete('banners/{id}', [BannerController::class, 'destroy']);

    Route::post('industrial-guide', [IndustrialGuideController::class, 'store']);
    Route::put('industrial-guide/{id}', [IndustrialGuideController::class, 'update']);
    Route::delete('industrial-guide/{id}', [IndustrialGuideController::class, 'destroy']);

    Route::post('magazines', [MagazineController::class, 'store']);
    Route::put('magazines/{id}', [MagazineController::class, 'update']);
    Route::delete('magazines/{id}', [MagazineController::class, 'destroy']);

    // Imagens das colunas sociais
    Route::delete('/social-columns/images/{id}', [SocialColumnController::class, 'destroyImage']);
    Route::post('/social-columns', [SocialColumnController::class, 'store']);
    Route::put('/social-columns/{id}', [SocialColumnController::class, 'update']);
    Route::delete('/social-columns/{id}', [SocialColumnController::class, 'destroy']);

});

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{slug}', [NewsController::class, 'show']);
Route::get('news-category/{categoryName}/{search?}',[NewsController::class,'newsCategory']);

//Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

//Route::get('sectors/{id}', [SectorController::class, 'show']);
Route::get('sectors', [SectorController::class, 'index']);

Route::get('/banners/top-e-side', [BannerController::class, 'getTopAndSideImages']);

Route::get('industrial-guide/{slug}', [IndustrialGuideController::class, 'show']);
Route::get('industrial-guides-sector/{sectorName}/{search?}',[IndustrialGuideController::class,'industrialGuideSector']);

Route::get('/social-columns/{search}', [SocialColumnController::class, 'index']);
Route::get('/social-column/{slug}', [SocialColumnController::class, 'show']);
