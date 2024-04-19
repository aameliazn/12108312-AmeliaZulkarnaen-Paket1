<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GlobalController;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RedirectAuth;
use Illuminate\Support\Facades\Route;

Route::middleware([CheckAuth::class])->group(function () {
    // ->middleware('roles:master,admin');

    Route::controller(AuthController::class)->group(function () {
        Route::get('register', 'register')->name('register')->withoutMiddleware([CheckAuth::class])->middleware([RedirectAuth::class]);
        Route::post('register/user', 'registerUser')->name('register.user')->withoutMiddleware([CheckAuth::class])->middleware([RedirectAuth::class]);

        Route::get('login', 'login')->name('login')->withoutMiddleware([CheckAuth::class])->middleware([RedirectAuth::class]);
        Route::post('login', 'loginAction')->name('login.action')->withoutMiddleware([CheckAuth::class])->middleware([RedirectAuth::class]);

        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller(GlobalController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::get('export', 'export')->name('export')->middleware('roles:master,admin');

        Route::get('admin', 'showAdmin')->name('admin')->middleware('roles:master');
        Route::post('admin', 'storeAdmin')->name('admin.add')->middleware('roles:master');
        Route::patch('admin/{id}', 'updateAdmin')->name('admin.edit')->middleware('roles:master');
        Route::delete('admin/{id}', 'destroyAdmin')->name('admin.delete')->middleware('roles:master');

        Route::get('category', 'showCategory')->name('category')->middleware('roles:master,admin');
        Route::post('category', 'storeCategory')->name('category.add')->middleware('roles:master,admin');
        Route::patch('category/{id}', 'updateCategory')->name('category.edit')->middleware('roles:master,admin');
        Route::delete('category/{id}', 'destroyCategory')->name('category.delete')->middleware('roles:master,admin');

        Route::get('book', 'showBook')->name('book');
        Route::post('book', 'storeBook')->name('book.add');
        Route::patch('book/{id}', 'updateBook')->name('book.edit');
        Route::delete('book/{id}', 'destroyBook')->name('book.delete');

        Route::get('collection', 'showCollection')->name('collection')->middleware('roles:user');
        Route::post('collection', 'storeCollection')->name('collection.add')->middleware('roles:user');
        Route::delete('collection/{id}', 'destroyCollection')->name('collection.delete')->middleware('roles:user');

        Route::post('lend', 'storeLend')->name('lend.add')->middleware('roles:user');
        Route::patch('lend/{id}', 'updateLend')->name('lend.edit')->middleware('roles:user');

        Route::post('review', 'storeReview')->name('review.add')->middleware('roles:user');
        Route::delete('review/{id}', 'destroyReview')->name('review.delete')->middleware('roles:user');
    });
});
