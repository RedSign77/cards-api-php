<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Deck;
use App\Models\Game;

class HomeController extends Controller
{
    public function index()
    {
        $totalCards = Card::count();
        $totalDecks = Deck::count();
        $totalGames = Game::count();
        $apiEndpoints = 55;

        return view('welcome', compact('totalCards', 'totalDecks', 'totalGames', 'apiEndpoints'));
    }
}
