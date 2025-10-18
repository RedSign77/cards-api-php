<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\WebsiteSettingResource\Pages;

use App\Filament\Resources\WebsiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteSetting extends EditRecord
{
    protected static string $resource = WebsiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle the different value fields based on type
        if (isset($data['type'])) {
            if ($data['type'] === 'textarea' && isset($data['value_textarea'])) {
                $data['value'] = $data['value_textarea'];
                unset($data['value_textarea']);
            } elseif ($data['type'] === 'boolean' && isset($data['value_boolean'])) {
                $data['value'] = $data['value_boolean'] ? '1' : '0';
                unset($data['value_boolean']);
            }
            // For text/email/url/number, 'value' is already set correctly
        }

        return $data;
    }
}
