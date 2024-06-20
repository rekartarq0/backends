<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Food\FoodController;
use App\Http\Controllers\Api\Quantity\QuantityController;
use App\Http\Controllers\Api\Table\ReservationsController;
use App\Http\Controllers\Api\Table\TableController;
use App\Models\Quantity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(
    function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        // Category
        Route::resource('category', CategoryController::class);
        Route::post('/category-update/{id}', [CategoryController::class, 'update'])->name('category-update');
        // Food Section
        Route::resource('food', FoodController::class);
        Route::post('/food-update/{id}', [FoodController::class, 'update'])->name('food-update');
        Route::get('food-category-input', [FoodController::class, 'category_input']);
        // Quantity
        Route::resource('quantity', QuantityController::class);
        Route::get('quantity-input', [QuantityController::class, 'quantity_input']);
        Route::post('/quantity-update/{id}', [QuantityController::class, 'update'])->name('quantity-update');
        // Table
        Route::resource('table', TableController::class);
        Route::post('/table-update/{id}', [TableController::class, 'update'])->name('table-update');
        // reversations
        Route::resource('reservation', ReservationsController::class);
        Route::post('/reservation-update/{id}', [ReservationsController::class, 'update'])->name('reservation-update');
        Route::get('reservation-input', [ReservationsController::class, 'reservation_input']);
    }
);
