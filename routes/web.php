<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return auth()->id() ? redirect(url('home')) : redirect(route('login'));
});

Auth::routes();
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('contents', ContentController::class);
    Route::get('contents/approve/{contentId}', [ContentController::class, 'approve'])->middleware('admin')->name('contents.approve');
    Route::get('contents/reject/{contentId}', [ContentController::class, 'reject'])->middleware('admin')->name('contents.reject');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
