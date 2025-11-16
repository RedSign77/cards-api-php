<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;

class MyOrders extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string $view = 'filament.pages.my-orders';

    protected static ?string $navigationLabel = 'My Orders';

    protected static ?string $title = 'My Orders';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = Order::where('buyer_id', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $shippedCount = Order::where('buyer_id', auth()->id())
            ->whereIn('status', ['shipped', 'delivered'])
            ->count();
        return $shippedCount > 0 ? 'success' : 'primary';
    }

    public function getTitle(): string | Htmlable
    {
        return 'My Orders';
    }

    public function getHeading(): string | Htmlable
    {
        return 'My Orders';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->where('buyer_id', auth()->id())->with(['seller', 'items.physicalCard']))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Seller')
                    ->searchable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money(fn (Order $record): string => $record->currency)
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'paypal' => 'PayPal',
                        'check' => 'Check',
                        'bank_transfer' => 'Bank Transfer',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (Order $record): string => $record->getPaymentStatusColor())
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('status')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn (Order $record): string => $record->getStatusColor())
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ordered On')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->modalWidth('4xl')
                    ->infolist(fn (Order $record): array => [
                        Components\Section::make('Order Information')
                            ->schema([
                                Components\TextEntry::make('order_number')
                                    ->label('Order Number')
                                    ->copyable()
                                    ->size('xl')
                                    ->weight('bold'),

                                Components\Grid::make(2)
                                    ->schema([
                                        Components\TextEntry::make('status')
                                            ->badge()
                                            ->color(fn (Order $record): string => $record->getStatusColor())
                                            ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),

                                        Components\TextEntry::make('payment_status')
                                            ->badge()
                                            ->color(fn (Order $record): string => $record->getPaymentStatusColor())
                                            ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                                    ]),

                                Components\Grid::make(2)
                                    ->schema([
                                        Components\TextEntry::make('created_at')
                                            ->label('Order Date')
                                            ->dateTime(),

                                        Components\TextEntry::make('payment_method')
                                            ->formatStateUsing(fn (string $state): string => match($state) {
                                                'paypal' => 'PayPal',
                                                'check' => 'Check',
                                                'bank_transfer' => 'Bank Transfer',
                                                default => $state,
                                            }),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Components\Section::make('Seller')
                            ->schema([
                                Components\TextEntry::make('seller.name'),
                                Components\TextEntry::make('seller.email')
                                    ->copyable(),
                            ])
                            ->columns(2)
                            ->columnSpan(1),

                        Components\Section::make('Shipping Address')
                            ->schema([
                                Components\TextEntry::make('shipping_name'),
                                Components\TextEntry::make('shipping_address_line1')
                                    ->label('Address'),
                                Components\TextEntry::make('shipping_city'),
                                Components\TextEntry::make('shipping_postal_code')
                                    ->label('Postal Code'),
                                Components\TextEntry::make('shipping_country'),
                            ])
                            ->columns(2)
                            ->columnSpan(1),

                        Components\Section::make('Items')
                            ->schema([
                                Components\RepeatableEntry::make('items')
                                    ->label('')
                                    ->schema([
                                        Components\TextEntry::make('physicalCard.title')
                                            ->label('Card'),
                                        Components\TextEntry::make('quantity')
                                            ->badge(),
                                        Components\TextEntry::make('price_per_unit')
                                            ->label('Price')
                                            ->money(fn ($record): string => $record->currency),
                                        Components\TextEntry::make('subtotal')
                                            ->money(fn ($record): string => $record->currency)
                                            ->weight('bold'),
                                    ])
                                    ->columns(4),
                            ])
                            ->columnSpan(2),

                        Components\Section::make('Order Total')
                            ->schema([
                                Components\Grid::make(2)
                                    ->schema([
                                        Components\TextEntry::make('subtotal')
                                            ->money(fn (Order $record): string => $record->currency),
                                        Components\TextEntry::make('shipping_cost')
                                            ->money(fn (Order $record): string => $record->currency),
                                    ]),
                                Components\TextEntry::make('total')
                                    ->money(fn (Order $record): string => $record->currency)
                                    ->size('xl')
                                    ->weight('bold')
                                    ->color('success'),
                            ])
                            ->columnSpan(2),
                    ]),

                Tables\Actions\Action::make('confirmReceived')
                    ->label('Confirm Received')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['shipped', 'delivered']) && !$record->buyer_confirmed_at)
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Order Received')
                    ->modalDescription('Confirm that you have received this order. The order will be marked as completed once the seller also confirms.')
                    ->action(function (Order $record): void {
                        $record->buyer_confirmed_at = now();

                        // If seller has also confirmed, mark as completed
                        if ($record->seller_confirmed_at) {
                            $record->status = 'completed';
                        } else {
                            $record->status = 'delivered';
                        }

                        $record->save();

                        // Send notifications
                        if (config('mail.enabled')) {
                            // Notify seller
                            $record->seller->notify(new \App\Notifications\OrderDeliveryConfirmed(
                                $record,
                                'buyer',
                                'seller'
                            ));

                            // Notify buyer (confirmation acknowledgment)
                            $record->buyer->notify(new \App\Notifications\OrderDeliveryConfirmed(
                                $record,
                                'buyer',
                                'buyer'
                            ));

                            // If completed, send status changed notification
                            if ($record->status === 'completed') {
                                $record->buyer->notify(new \App\Notifications\OrderStatusChanged(
                                    $record,
                                    'delivered',
                                    'completed',
                                    'buyer'
                                ));
                                $record->seller->notify(new \App\Notifications\OrderStatusChanged(
                                    $record,
                                    'delivered',
                                    'completed',
                                    'seller'
                                ));
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title('Order confirmed')
                            ->body($record->status === 'completed'
                                ? 'Order completed successfully!'
                                : 'Order marked as received. Waiting for seller confirmation.')
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No orders yet')
            ->emptyStateDescription('You haven\'t placed any orders yet. Start shopping in the marketplace!')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\Action::make('browse')
                    ->label('Browse Marketplace')
                    ->url(route('filament.admin.pages.marketplace'))
                    ->icon('heroicon-o-shopping-bag'),
            ]);
    }
}
