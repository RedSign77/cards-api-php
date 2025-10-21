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
        // Marketplace Statistics
        $activeListings = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)->count();
        $totalSellers = User::whereHas('physicalCards', function ($query) {
            $query->where('status', PhysicalCard::STATUS_APPROVED);
        })->count();
        $cardsAvailable = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)->sum('quantity');
        $pendingReviews = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)->count();
        $averagePrice = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->where('price_per_unit', '>', 0)
            ->avg('price_per_unit') ?? 0;
        $totalUsers = User::count();

        // Featured Listings - Recently approved cards
        $featuredListings = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->with('user')
            ->latest('approved_at')
            ->take(8)
            ->get();

        return view('welcome', compact(
            'activeListings',
            'totalSellers',
            'cardsAvailable',
            'pendingReviews',
            'averagePrice',
            'totalUsers',
            'featuredListings'
        ));
    }
}
