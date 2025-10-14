<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Resources\DeckResource\Pages;

use App\Filament\Resources\DeckResource;
use Filament\Resources\Pages\ViewRecord;

class ViewDeck extends ViewRecord
{
    protected static string $resource = DeckResource::class;

    protected static ?string $title = 'View Deck';
}
