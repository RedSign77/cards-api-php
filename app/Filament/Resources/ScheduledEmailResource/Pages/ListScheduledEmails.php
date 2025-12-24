<?php

/*
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\ScheduledEmailResource\Pages;

use App\Filament\Resources\ScheduledEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScheduledEmails extends ListRecords
{
    protected static string $resource = ScheduledEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
