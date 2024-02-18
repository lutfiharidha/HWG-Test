<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AuthController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth.jwt')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/category', [BookCategoryController::class, 'index']);
    Route::post('/category', [BookCategoryController::class, 'store']);
    Route::put('/category/{id}', [BookCategoryController::class, 'update']);
    Route::delete('/category/{id}', [BookCategoryController::class, 'delete']);

    Route::get('/book', [BookController::class, 'index']);
    Route::post('/book', [BookController::class, 'store']);
    Route::put('/book/{id}', [BookController::class, 'update']);
    Route::delete('/book/{id}', [BookController::class, 'delete']);

    Route::get('/borrow', [BorrowingController::class, 'index']);
    Route::post('/borrow', [BorrowingController::class, 'store']);
    Route::put('/borrow/return/{id}', [BorrowingController::class, 'return']);
    Route::delete('/borrow/{id}', [BorrowingController::class, 'delete']);
});



