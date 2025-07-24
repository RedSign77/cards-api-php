<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

use App\Http\Controllers\Api\v1\Cards;
use App\Http\Controllers\Api\v1\CardTypes;
use App\Http\Controllers\Api\v1\Decks;
use App\Http\Controllers\Api\v1\Games;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::post('/user/register', [ApiController::class, 'register']);
Route::post('/user/login', [ApiController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::get('/user/profile', [ApiController::class, 'profile']);
    Route::get('/user/logout', [ApiController::class, 'logout']);

    Route::group(['prefix' => 'v1'], static function () {
        Route::apiResource('cards', Cards::class);
        Route::apiResource('cardtypes', CardTypes::class);
        Route::apiResource('decks', Decks::class);
        Route::apiResource('games', Games::class);
    });

});

