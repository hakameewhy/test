<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('menu');
});

Route::resource('/products', \App\Http\Controllers\ProductController::class);
Route::resource('/konsumens', \App\Http\Controllers\KonsumenController::class);