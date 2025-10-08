<?php

use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

// Web routes for PhotoController at root (no /photo prefix)
Route::get('/', [PhotoController::class, 'index'])->name('index');
// create must come before show (to avoid being captured by {photo})
Route::get('/create', [PhotoController::class, 'create'])->name('create');
Route::post('/', [PhotoController::class, 'store'])->name('store');
Route::get('/{photo}', [PhotoController::class, 'show'])->name('show');

// API upload endpoint (kept under /api)
Route::post('api/photo', [ApiPhotoController::class, 'store']);
