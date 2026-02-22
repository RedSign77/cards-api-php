<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services;

use App\Models\Card;
use App\Models\Deck;
use App\Models\WebsiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DeckPdfService
{
    /**
     * Expand deck cards by quantity, sorted by card type then deck order.
     *
     * @return array<Card>
     */
    public function expandCards(Deck $deck): array
    {
        $deckData = $deck->deck_data ?? [];

        if (empty($deckData)) {
            return [];
        }

        // Load all card IDs from deck data
        $cardIds = array_column($deckData, 'card_id');
        $cards   = Card::whereIn('id', $cardIds)
            ->with('cardType')
            ->get()
            ->keyBy('id');

        $expanded = [];

        // Maintain deck order, then group by type on sort
        $items = collect($deckData)->map(function ($item, $index) use ($cards) {
            $card = $cards->get($item['card_id'] ?? null);
            return [
                'card'     => $card,
                'quantity' => (int) ($item['quantity'] ?? 1),
                'order'    => $index,
                'type_id'  => $card?->type_id ?? 0,
            ];
        })->sortBy([
            ['type_id', 'asc'],
            ['order', 'asc'],
        ]);

        foreach ($items as $item) {
            if ($item['card'] === null) {
                continue;
            }
            for ($i = 0; $i < $item['quantity']; $i++) {
                $expanded[] = $item['card'];
            }
        }

        return $expanded;
    }

    /**
     * Resolve the background image for the card layer:
     * - If the card has its own illustration, use that.
     * - Otherwise fall through: deck override → system default → built-in placeholder.
     */
    protected function resolveImagePath(Card $card, Deck $deck): string
    {
        if (!empty($card->image)) {
            $path = Storage::disk('public')->path($card->image);
            if (file_exists($path)) {
                return $path;
            }
        }

        // 1. Deck override
        if (!empty($deck->pdf_background)) {
            $path = Storage::disk('public')->path($deck->pdf_background);
            if (file_exists($path)) {
                return $path;
            }
        }

        // 2. System default
        $setting = WebsiteSetting::get('card_pdf_background');
        if (!empty($setting)) {
            $path = Storage::disk('public')->path($setting);
            if (file_exists($path)) {
                return $path;
            }
        }

        // 3. Built-in fallback
        return public_path('images/cardsforge-back.png');
    }

    /**
     * Generate and stream a PDF download response.
     */
    public function download(Deck $deck): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        $cards = $this->expandCards($deck);
        $pages = array_chunk($cards, 9);

        // Resolve local paths for images (dompdf needs filesystem paths, not URLs)
        $cardWidth  = $deck->game->card_width_mm  ?? 63.5;
        $cardHeight = $deck->game->card_height_mm ?? 88.9;

        // Resolve overlay mode: deck → system default → 'dark'
        $overlayMode = $deck->pdf_overlay
            ?? WebsiteSetting::get('card_pdf_overlay', 'dark');

        $pagesData = [];
        foreach ($pages as $pageCards) {
            $pageItems = [];
            foreach ($pageCards as $card) {
                $pageItems[] = [
                    'card'      => $card,
                    'imagePath' => $this->resolveImagePath($card, $deck),
                ];
            }
            $pagesData[] = $pageItems;
        }

        $pdf = Pdf::loadView('pdf.deck-print', [
            'deck'        => $deck,
            'pages'       => $pagesData,
            'cardWidth'   => $cardWidth,
            'cardHeight'  => $cardHeight,
            'overlayMode' => $overlayMode,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', false);

        $filename = 'deck-' . str($deck->deck_name)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
