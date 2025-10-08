<?php

use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/photo');
});

Route::prefix('/api')->group(function () {
    Route::post('photo', [ApiPhotoController::class, 'store'])->name('api.photo.store');
});
Route::resource('photo', PhotoController::class)->only('index', 'create', 'store', 'show');

