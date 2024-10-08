<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\UserCategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', [AuthController::class, 'user']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/quote-of-the-day', [QuoteController::class, 'quoteOfTheDay']);
Route::get('/missed-quotes', [QuoteController::class, 'missedQuotes']);
Route::post('/favorite/toggle', [FavoriteController::class, 'toggleFavorite']);
Route::get('/favorites', [FavoriteController::class, 'getFavorites']);
Route::get('/user/categories', [UserCategoryController::class, 'getUserCategory']);
Route::post('/user/categories', [UserCategoryController::class, 'update']);
Route::get('quote/{id}/is-favorite', [QuoteController::class, 'isFavorite']);
