<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Services\DeckPdfService;
use Illuminate\Http\Request;

class DeckPdfController extends Controller
{
    public function download(Request $request, Deck $deck)
    {
        // Ensure the deck belongs to the authenticated user
        abort_unless((int) $deck->creator_id === (int) auth()->id(), 403);

        return app(DeckPdfService::class)->download($deck);
    }
}
