<?php

use Illuminate\Http\Request;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndustrialGuideController;
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
    Route::get('news/panel/{search?}/{category?}', [NewsController::class, 'panel']);
    Route::get('news', [NewsController::class, 'index']);

    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories', [CategoryController::class, 'panel']);

    Route::get('banners', [BannerController::class, 'index']);
    Route::post('banners', [BannerController::class, 'store']);
    Route::delete('banners/{id}', [BannerController::class, 'destroy']);

    Route::post('industrial-guide', [IndustrialGuideController::class, 'store']);
    Route::put('industrial-guide/{id}', [IndustrialGuideController::class, 'update']);
    Route::delete('industrial-guide/{id}', [IndustrialGuideController::class, 'destroy']);
});

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);
Route::get('news-category/{categoryId}/{search?}',[NewsController::class,'newsCategory']);

Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

Route::get('/banners/top-e-side', [BannerController::class, 'getTopAndSideImages']);

Route::get('industrial-guide/{id}', [IndustrialGuideController::class, 'show']);
Route::get('industrial-guides/{search}', [IndustrialGuideController::class, 'index']);

