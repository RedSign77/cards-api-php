<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

use App\Http\Controllers\Api\v1\Cards;
use App\Http\Controllers\Api\v1\CardTypes;
use App\Http\Controllers\Api\v1\Decks;
use App\Http\Controllers\Api\v1\Games;
use Illuminate\Support\Facades\Route;

Route::apiResource('cards', Cards::class);
Route::apiResource('cardtypes', CardTypes::class);
Route::apiResource('decks', Decks::class);
Route::apiResource('games', Games::class);
