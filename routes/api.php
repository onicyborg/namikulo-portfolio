<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\PortfolioApiController;
use App\Http\Controllers\Api\TestimonialApiController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/portfolios', [PortfolioApiController::class, 'index']);
Route::get('/portfolios/{id}', [PortfolioApiController::class, 'show']);

Route::get('/testimonials', [TestimonialApiController::class, 'index']);
Route::get('/testimonials/{id}', [TestimonialApiController::class, 'show']);

Route::get('/categories', [CategoryApiController::class, 'index']);
