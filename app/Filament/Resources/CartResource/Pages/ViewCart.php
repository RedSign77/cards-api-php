<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\CartResource\Pages;

use App\Filament\Resources\CartResource;
use App\Models\Cart;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewCart extends ViewRecord
{
    protected static string $resource = CartResource::class;

    protected static ?string $title = 'View Cart';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Cart Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_items')
                            ->label('Total Items')
                            ->getStateUsing(function (Cart $record) {
                                return $record->getTotalItems();
                            })
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('total_price')
                            ->label('Total Price')
                            ->money('USD')
                            ->getStateUsing(function (Cart $record) {
                                return $record->getTotalPrice();
                            })
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Cart Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\ImageEntry::make('physicalCard.image')
                                    ->label('Image')
                                    ->circular()
                                    ->defaultImageUrl(url('/images/placeholder.png')),
                                Infolists\Components\TextEntry::make('physicalCard.title')
                                    ->label('Card Title')
                                    ->weight(FontWeight::Bold),
                                Infolists\Components\TextEntry::make('physicalCard.set')
                                    ->label('Set'),
                                Infolists\Components\TextEntry::make('physicalCard.condition')
                                    ->label('Condition')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Quantity'),
                                Infolists\Components\TextEntry::make('physicalCard.price_per_unit')
                                    ->label('Price per Unit')
                                    ->money('USD'),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('USD')
                                    ->getStateUsing(function ($record) {
                                        return $record->getSubtotal();
                                    })
                                    ->weight(FontWeight::Bold),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}
