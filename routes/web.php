<?php

use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/photo');
});

Route::prefix('api')->group(function () {
    Route::resource('photo', ApiPhotoController::class);

});
Route::Resource('photo', PhotoController::class);
