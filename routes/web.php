<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrixController;

Route::get('/', function () {
    return view('welcome');
});

// Trix post routes
Route::get('/trix', [TrixController::class, 'index'])->name('trix.create');
Route::post('/trix/store', [TrixController::class, 'store'])->name('trix.store');
Route::post('/trix/upload', [TrixController::class, 'upload'])->name('trix.upload');
Route::get('/trix/posts', [TrixController::class, 'showPosts'])->name('trix.posts');

// Edit / Update
Route::get('/trix/edit/{id}', [TrixController::class, 'edit'])->name('trix.edit');
Route::put('/trix/update/{id}', [TrixController::class, 'update'])->name('trix.update');

// Delete
Route::delete('/trix/post/{id}', [TrixController::class, 'destroy'])->name('trix.destroy');

// Status toggle
Route::patch('/trix/toggle-status/{id}', [TrixController::class, 'toggleStatus'])->name('trix.toggle');