<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\CartItem;
use App\Models\PhysicalCard;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class MyCart extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.my-cart';

    protected static ?string $navigationLabel = 'My Cart';

    protected static ?string $title = 'My Shopping Cart';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Marketplace';

    public function getTitle(): string | Htmlable
    {
        return 'My Shopping Cart';
    }

    public function getHeading(): string | Htmlable
    {
        return 'My Shopping Cart';
    }

    public function getSubheading(): ?string
    {
        $cart = auth()->user()->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return 'Your cart is empty';
        }

        $totalItems = $cart->getTotalItems();
        $totalPrice = $cart->getTotalPrice();

        return "You have {$totalItems} item(s) in your cart • Total: $" . number_format($totalPrice, 2);
    }

    public static function getNavigationBadge(): ?string
    {
        $cart = auth()->user()->cart;
        if (!$cart) {
            return null;
        }

        $totalItems = $cart->getTotalItems();
        return $totalItems > 0 ? (string) $totalItems : null;
    }

    public function table(Table $table): Table
    {
        $cart = auth()->user()->getOrCreateCart();

        return $table
            ->query(CartItem::query()->where('cart_id', $cart->id)->with(['physicalCard.user']))
            ->columns([
                Tables\Columns\ImageColumn::make('physicalCard.image')
                    ->label('Image')
                    ->disk('public')
                    ->size(80)
                    ->defaultImageUrl(url('/images/placeholder-card.png')),

                Tables\Columns\TextColumn::make('physicalCard.title')
                    ->label('Card')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (CartItem $record): string => $record->physicalCard->set ? "Set: {$record->physicalCard->set}" : ''),

                Tables\Columns\TextColumn::make('physicalCard.condition')
                    ->label('Condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mint' => 'success',
                        'near_mint' => 'info',
                        'excellent' => 'primary',
                        'good' => 'warning',
                        'played' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('physicalCard.user.name')
                    ->label('Seller')
                    ->searchable(),

                Tables\Columns\TextColumn::make('physicalCard.price_per_unit')
                    ->label('Unit Price')
                    ->money(fn (CartItem $record): string => $record->physicalCard->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money(fn (CartItem $record): string => $record->physicalCard->currency)
                    ->getStateUsing(fn (CartItem $record): float => $record->getSubtotal())
                    ->weight('bold')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('updateQuantity')
                    ->label('Update Qty')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(fn (CartItem $record): int => $record->quantity)
                            ->minValue(1)
                            ->maxValue(fn (CartItem $record): int => $record->physicalCard->quantity)
                            ->required()
                            ->helperText(fn (CartItem $record): string => "Available stock: {$record->physicalCard->quantity}"),
                    ])
                    ->action(function (CartItem $record, array $data): void {
                        $newQuantity = (int) $data['quantity'];

                        // Validate against available stock
                        if ($newQuantity > $record->physicalCard->quantity) {
                            Notification::make()
                                ->warning()
                                ->title('Insufficient stock')
                                ->body("Only {$record->physicalCard->quantity} available")
                                ->send();
                            return;
                        }

                        $record->quantity = $newQuantity;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Quantity updated')
                            ->body("Quantity updated to {$newQuantity}")
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove from cart?')
                    ->modalDescription(fn (CartItem $record): string => "Are you sure you want to remove '{$record->physicalCard->title}' from your cart?")
                    ->successNotificationTitle('Item removed from cart'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove selected')
                        ->modalHeading('Remove items from cart?')
                        ->modalDescription('Are you sure you want to remove the selected items from your cart?')
                        ->successNotificationTitle('Items removed from cart'),
                ]),
            ])
            ->emptyStateHeading('Your cart is empty')
            ->emptyStateDescription('Browse the marketplace and add items to your cart')
            ->emptyStateIcon('heroicon-o-shopping-cart')
            ->emptyStateActions([
                Tables\Actions\Action::make('browse')
                    ->label('Browse Marketplace')
                    ->url(route('filament.admin.pages.marketplace'))
                    ->icon('heroicon-o-shopping-bag')
                    ->color('primary'),
            ])
            ->paginated([10, 25, 50]);
    }
}
