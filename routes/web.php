<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->controller(Authentication::class)->group(function () {
    Route::post('register','RegisterFunc');
});