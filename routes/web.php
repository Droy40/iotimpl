<?php

use App\Http\Controllers\ApiPhotoController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::resource('api/photo',ApiPhotoController::class)->only('store');
Route::resource('',PhotoController::class)->only(['index','show','store','create']);

