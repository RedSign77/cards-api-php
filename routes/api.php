<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
use App\Http\Controllers\Cards;
use App\Http\Controllers\CardTypes;
use App\Http\Controllers\Decks;
use App\Http\Controllers\Games;
use Illuminate\Support\Facades\Route;

Route::apiResource('cards', Cards::class);
Route::apiResource('cardtypes', CardTypes::class);
Route::apiResource('decks', Decks::class);
Route::apiResource('games', Games::class);
