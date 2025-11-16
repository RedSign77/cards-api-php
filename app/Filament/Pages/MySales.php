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
use Filament\Notifications\Notification;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\Support\Htmlable;

class MySales extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.pages.my-sales';

    protected static ?string $navigationLabel = 'My Sales';

    protected static ?string $title = 'My Sales';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        $count = Order::where('seller_id', auth()->id())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = Order::where('seller_id', auth()->id())
            ->where('status', 'pending')
            ->count();
        return $pendingCount > 0 ? 'warning' : 'primary';
    }

    public function getTitle(): string | Htmlable
    {
        return 'My Sales';
    }

    public function getHeading(): string | Htmlable
    {
        return 'My Sales';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->where('seller_id', auth()->id())->with(['buyer', 'items.physicalCard']))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Buyer')
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

                        Components\Section::make('Buyer')
                            ->schema([
                                Components\TextEntry::make('buyer.name'),
                                Components\TextEntry::make('buyer.email')
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

                        Components\Section::make('Payment Information for Buyer')
                            ->schema([
                                Components\TextEntry::make('seller_payment_info')
                                    ->label('')
                                    ->markdown()
                                    ->default(fn (Order $record): string => $record->seller_payment_info ?: 'No payment information provided yet')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),
                    ]),

                Tables\Actions\Action::make('editPaymentInfo')
                    ->label('Edit Payment Info')
                    ->icon('heroicon-o-credit-card')
                    ->color('warning')
                    ->form(fn (Order $record): array => [
                        Textarea::make('seller_payment_info')
                            ->label('Payment Information for Buyer')
                            ->default(fn () => $record->seller_payment_info ?: $this->getDefaultPaymentInfo())
                            ->rows(10)
                            ->helperText('Provide payment instructions for the buyer (PayPal, bank transfer, etc.)')
                            ->placeholder('Example:\n\nPayPal: your@email.com\n\nBank Transfer:\nBank: Your Bank Name\nAccount: 1234567890\nRouting: 987654321\n\nPlease include the order number in the payment memo.')
                            ->columnSpanFull(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldPaymentInfo = $record->seller_payment_info;
                        $record->seller_payment_info = $data['seller_payment_info'];
                        $record->save();

                        // Send notification to buyer if payment info changed
                        if (config('mail.enabled') && $oldPaymentInfo !== $data['seller_payment_info']) {
                            $record->buyer->notify(new \App\Notifications\PaymentInfoUpdated($record));
                        }

                        Notification::make()
                            ->success()
                            ->title('Payment information updated')
                            ->body('The buyer has been notified of the payment details.')
                            ->send();
                    }),

                Tables\Actions\Action::make('markPacking')
                    ->label('Mark as Packing')
                    ->icon('heroicon-o-archive-box')
                    ->color('info')
                    ->visible(fn (Order $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $oldStatus = $record->status;
                        $record->status = 'packing';
                        $record->save();

                        // Send notification to buyer
                        if (config('mail.enabled')) {
                            $record->buyer->notify(new \App\Notifications\OrderStatusChanged(
                                $record,
                                $oldStatus,
                                'packing',
                                'buyer'
                            ));
                        }

                        Notification::make()
                            ->success()
                            ->title('Order updated')
                            ->body('Order marked as packing.')
                            ->send();
                    }),

                Tables\Actions\Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['pending', 'packing']))
                    ->form([
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'paypal' => 'PayPal',
                                'check' => 'Check',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->default(fn (Order $record): string => $record->payment_method)
                            ->required()
                            ->helperText('Update the payment method if needed'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->status;
                        $oldPaymentMethod = $record->payment_method;

                        $record->status = 'paid';
                        $record->payment_status = 'paid';
                        $record->payment_method = $data['payment_method'];
                        $record->save();

                        // Send notifications
                        if (config('mail.enabled')) {
                            // Notify about status change
                            $record->buyer->notify(new \App\Notifications\OrderStatusChanged(
                                $record,
                                $oldStatus,
                                'paid',
                                'buyer'
                            ));

                            // Notify if payment method changed
                            if ($oldPaymentMethod !== $data['payment_method']) {
                                $record->buyer->notify(new \App\Notifications\PaymentMethodChanged(
                                    $record,
                                    $oldPaymentMethod,
                                    $data['payment_method']
                                ));
                            }
                        }

                        Notification::make()
                            ->success()
                            ->title('Payment confirmed')
                            ->body('Order marked as paid.')
                            ->send();
                    }),

                Tables\Actions\Action::make('markShipped')
                    ->label('Mark as Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['packing', 'paid']))
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $oldStatus = $record->status;
                        $record->status = 'shipped';
                        $record->save();

                        // Send notification to buyer
                        if (config('mail.enabled')) {
                            $record->buyer->notify(new \App\Notifications\OrderStatusChanged(
                                $record,
                                $oldStatus,
                                'shipped',
                                'buyer'
                            ));
                        }

                        Notification::make()
                            ->success()
                            ->title('Order shipped')
                            ->body('Order marked as shipped.')
                            ->send();
                    }),

                Tables\Actions\Action::make('confirmDelivery')
                    ->label('Confirm Delivery')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['shipped', 'delivered']) && !$record->seller_confirmed_at)
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Delivery')
                    ->modalDescription('Confirm that the order has been delivered. The order will be marked as completed once the buyer also confirms.')
                    ->action(function (Order $record): void {
                        $record->seller_confirmed_at = now();

                        // If buyer has also confirmed, mark as completed
                        if ($record->buyer_confirmed_at) {
                            $record->status = 'completed';
                        } else {
                            $record->status = 'delivered';
                        }

                        $record->save();

                        // Send notifications
                        if (config('mail.enabled')) {
                            // Notify buyer
                            $record->buyer->notify(new \App\Notifications\OrderDeliveryConfirmed(
                                $record,
                                'seller',
                                'buyer'
                            ));

                            // Notify seller (confirmation acknowledgment)
                            $record->seller->notify(new \App\Notifications\OrderDeliveryConfirmed(
                                $record,
                                'seller',
                                'seller'
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
                            ->title('Delivery confirmed')
                            ->body($record->status === 'completed'
                                ? 'Order completed successfully!'
                                : 'Order marked as delivered. Waiting for buyer confirmation.')
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No sales yet')
            ->emptyStateDescription('You haven\'t received any orders yet.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    protected function getDefaultPaymentInfo(): string
    {
        $seller = auth()->user();
        $info = [];

        if ($seller->paypal_email) {
            $info[] = "**PayPal:**\n" . $seller->paypal_email;
        }

        if ($seller->bank_name || $seller->bank_account_number) {
            $bankInfo = "**Bank Transfer:**\n";
            if ($seller->bank_name) {
                $bankInfo .= "Bank: " . $seller->bank_name . "\n";
            }
            if ($seller->bank_account_name) {
                $bankInfo .= "Account Name: " . $seller->bank_account_name . "\n";
            }
            if ($seller->bank_account_number) {
                $bankInfo .= "Account Number: " . $seller->bank_account_number . "\n";
            }
            if ($seller->bank_routing_number) {
                $bankInfo .= "Routing Number: " . $seller->bank_routing_number . "\n";
            }
            if ($seller->bank_swift_code) {
                $bankInfo .= "SWIFT Code: " . $seller->bank_swift_code . "\n";
            }
            $info[] = $bankInfo;
        }

        if ($seller->payment_notes) {
            $info[] = "**Additional Notes:**\n" . $seller->payment_notes;
        }

        return !empty($info) ? implode("\n\n", $info) : '';
    }
}
