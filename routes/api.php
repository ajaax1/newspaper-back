<?php

use Illuminate\Http\Request;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('admin/users/{search?}', [UserController::class, 'index']);
    Route::post('admin/users', [UserController::class, 'store']);
    Route::get('admin/user/{id}', [UserController::class, 'show']);
    Route::put('admin/users/{id}', [UserController::class, 'update']);
    Route::delete('admin/users/{id}', [UserController::class, 'destroy']);

    Route::post('admin/news', [NewsController::class, 'store']);
    Route::put('admin/news/{id}', [NewsController::class, 'update']);
    Route::delete('admin/news/{id}', [NewsController::class, 'destroy']);
    Route::get('admin/admin/news/{search?}/{category?}', [NewsController::class, 'panel']);
    Route::get('admin/news', [NewsController::class, 'index']);

    Route::put('admin/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('admin/categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('admin/categories/{id}', [CategoryController::class, 'show']);
    Route::post('admin/categories', [CategoryController::class, 'store']);
    Route::get('admin/categories', [CategoryController::class, 'panel']);

    Route::get('admin/banners', [BannerController::class, 'index']);
    Route::post('admin/banners',[BannerController::class,'store']);
    Route::delete('admin/banners/{id}',[BannerController::class,'destroy']);
});

Route::get('news', [NewsController::class, 'index']);
Route::get('news/{id}', [NewsController::class, 'show']);
Route::get('news-category/{categoryId}/{search?}',[NewsController::class,'newsCategory']);

Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

Route::get('/banners/top-e-side', [BannerController::class, 'getTopAndSideImages']);

