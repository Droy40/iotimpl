<?php

use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/photo');
});

Route::prefix('/api')->group(function () {
    Route::get('photo', [ApiPhotoController::class, 'index'])->name('api.photo.index');
    Route::post('photo', [ApiPhotoController::class, 'store'])->name('api.photo.store');
});
Route::resource('photo', PhotoController::class);

Route::prefix('/photo')->group(function () {
    Route::get('', [PhotoController::class, 'index'])->name('photo.index');
    Route::get('create', [PhotoController::class, 'create'])->name('photo.create');
    Route::post('', [PhotoController::class, 'store'])->name('photo.store');
    Route::get('{photo}', [PhotoController::class, 'show'])->name('photo.show');
});

//Route::get('/api/photo',[ApiPhotoController::class,'index']);

