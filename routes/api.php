<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Food\FoodController;
use App\Http\Controllers\Api\Quantity\QuantityController;
use App\Models\Quantity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(
    function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::resource('category', CategoryController::class);
        Route::post('/category-update/{id}', [CategoryController::class,'update'])->name('category-update');
        Route::resource('food', FoodController::class);
        Route::post('/food-update/{id}', [FoodController::class,'update'])->name('food-update');
        
        Route::get('food-category-input',[FoodController::class,'category_input']);
        Route::resource('quantity', QuantityController::class);
        Route::post('/quantity-update/{id}', [QuantityController::class,'update'])->name('quantity-update');
    }
);
