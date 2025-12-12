<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrixController;

Route::get('/', function () {
    return view('welcome');
});

// Display the Trix editor form
Route::get('trix', [TrixController::class, 'index']);

// Handle image/file uploads from Trix editor
Route::post('trix/upload', [TrixController::class, 'upload'])->name('trix.upload');

// Save the post content submitted via Trix editor
Route::post('trix/store', [TrixController::class, 'store'])->name('trix.store');

// Display all saved posts
Route::get('/posts', [TrixController::class, 'showPosts'])->name('posts');