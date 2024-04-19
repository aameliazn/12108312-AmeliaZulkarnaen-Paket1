<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register/user', 'registerUser')->name('register.user');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->name('logout');
});

Route::controller(GlobalController::class)->group(function () {
    Route::get('dashboard', 'index')->name('dashboard');

    Route::get('admin', 'showAdmin')->name('admin');
    Route::post('admin', 'storeAdmin')->name('admin.add');
    Route::patch('admin/{id}', 'updateAdmin')->name('admin.edit');
    Route::delete('admin/{id}', 'destroyAdmin')->name('admin.delete');

    Route::get('category', 'showCategory')->name('category');
    Route::post('category', 'storeCategory')->name('category.add');
    Route::patch('category/{id}', 'updateCategory')->name('category.edit');
    Route::delete('category/{id}', 'destroyCategory')->name('category.delete');

    Route::get('book', 'showBook')->name('book');
    Route::post('book', 'storeBook')->name('book.add');
    Route::patch('book/{id}', 'updateBook')->name('book.edit');
    Route::delete('book/{id}', 'destroyBook')->name('book.delete');

    Route::get('collection', 'showCollection')->name('collection');
});
