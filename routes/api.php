<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;

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
Route::middleware(['json.response'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login')->name('auth.login');
        Route::post('register', 'register')->name('auth.register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::controller(AuthorController::class)->group(function () {
            Route::get('authors', 'index')->name('author.index')->middleware('permission:view authors');
            Route::post('change-status', 'changeAuthorStatus')->name('author.changeAuthorStatus')->middleware('permission:change user status');
        });

        Route::controller(BookController::class)->group(function () {
            Route::get('books', 'index')->name('book.index')->middleware('permission:view books');
            Route::post('create-book', 'store')->name('book.store')->middleware('permission:create books');
        });
    });
});
