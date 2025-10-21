<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PhysicalCard;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Platform Statistics
        $totalUsers = User::count();
        $totalCards = \App\Models\Card::count();
        $totalGames = \App\Models\Game::count();
        $totalDecks = \App\Models\Deck::count();

        // Marketplace Statistics (Secondary)
        $activeListings = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)->count();
        $totalSellers = User::whereHas('physicalCards', function ($query) {
            $query->where('status', PhysicalCard::STATUS_APPROVED);
        })->count();
        $cardsAvailable = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)->sum('quantity');

        // Featured Listings - Recently approved cards (reduced to 4)
        $featuredListings = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->with('user')
            ->latest('approved_at')
            ->take(4)
            ->get();

        return view('welcome', compact(
            'totalUsers',
            'totalCards',
            'totalGames',
            'totalDecks',
            'activeListings',
            'totalSellers',
            'cardsAvailable',
            'featuredListings'
        ));
    }
}
