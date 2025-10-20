<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PhysicalCard;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display marketplace browse page with all approved listings
     */
    public function index(Request $request)
    {
        $query = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->with('user');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('set', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Condition filter
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // Price range filter
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price_per_unit', '>=', $request->min_price);
        }
        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price_per_unit', '<=', $request->max_price);
        }

        // Tradeable filter
        if ($request->has('tradeable') && $request->tradeable !== '') {
            $query->where('tradeable', (bool) $request->tradeable);
        }

        // Language filter
        if ($request->has('language') && $request->language) {
            $query->where('language', $request->language);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price_per_unit', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_unit', 'desc');
                break;
            case 'oldest':
                $query->oldest('approved_at');
                break;
            case 'latest':
            default:
                $query->latest('approved_at');
                break;
        }

        $cards = $query->paginate(24);

        // Get filter options
        $conditions = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->distinct()
            ->pluck('condition')
            ->filter()
            ->sort()
            ->values();

        $languages = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->distinct()
            ->pluck('language')
            ->filter()
            ->sort()
            ->values();

        return view('marketplace.index', compact('cards', 'conditions', 'languages'));
    }

    /**
     * Display individual card detail page
     */
    public function show(PhysicalCard $card)
    {
        // Only show approved cards
        if ($card->status !== PhysicalCard::STATUS_APPROVED) {
            abort(404, 'Card not found or not available');
        }

        $card->load('user');

        // Get similar cards from the same set
        $similarCards = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->where('id', '!=', $card->id)
            ->where(function ($query) use ($card) {
                $query->where('set', $card->set)
                    ->orWhere('title', 'like', '%' . substr($card->title, 0, 10) . '%');
            })
            ->with('user')
            ->take(4)
            ->get();

        return view('marketplace.show', compact('card', 'similarCards'));
    }
}
