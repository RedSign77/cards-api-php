<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::get('/api/documentation', function () {
    return response()->file(public_path('api-documentation.html'));
});
