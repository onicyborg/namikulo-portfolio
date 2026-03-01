<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function() {
        return view('dashboard');
    })->name('dashboard');

	Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
	Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
	Route::put('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
	Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

	Route::get('/portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
	Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
	Route::put('/portfolios/{id}', [PortfolioController::class, 'update'])->name('portfolios.update');
	Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');

	Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
	Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
	Route::put('/testimonials/{id}', [TestimonialController::class, 'update'])->name('testimonials.update');
	Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
