<?php

namespace App\Filament\Resources\CardTypeResource\Pages;

use App\Filament\Resources\CardTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCardType extends CreateRecord
{
    protected static string $resource = CardTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
