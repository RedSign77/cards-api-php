<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardType;
use App\Models\Deck;
use App\Models\Game;
use App\Models\User;
use App\Models\Hexa;
use App\Models\Figure;

class HomeController extends Controller
{
    public function index()
    {
        $totalCards = Card::count();
        $totalCardTypes = CardType::count();
        $totalDecks = Deck::count();
        $totalGames = Game::count();
        $totalUsers = User::count();
        $totalHexas = Hexa::count();
        $totalFigures = Figure::count();
        $apiEndpoints = 55;

        return view('welcome', compact('totalCards', 'totalCardTypes', 'totalDecks', 'totalGames', 'totalUsers', 'totalHexas', 'totalFigures', 'apiEndpoints'));
    }
}
