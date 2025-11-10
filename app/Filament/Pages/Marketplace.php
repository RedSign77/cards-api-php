<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\PhysicalCard;
use App\Models\CartItem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Marketplace extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string $view = 'filament.pages.marketplace';

    protected static ?string $navigationLabel = 'Browse';

    protected static ?string $title = 'Browse Marketplace';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string | Htmlable
    {
        return 'Browse Marketplace';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Browse Marketplace';
    }

    public function getSubheading(): ?string
    {
        return 'Discover and browse physical collectible cards from verified sellers';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PhysicalCard::query()->where('status', PhysicalCard::STATUS_APPROVED))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->size(80)
                    ->defaultImageUrl(url('/images/placeholder-card.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (PhysicalCard $record): string => $record->set ? "Set: {$record->set}" : ''),

                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mint' => 'success',
                        'near_mint' => 'info',
                        'excellent' => 'primary',
                        'good' => 'warning',
                        'played' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Original Price')
                    ->sortable()
                    ->money(fn (PhysicalCard $record): string => $record->currency)
                    ->description(fn (PhysicalCard $record): string => "Seller's currency"),

                Tables\Columns\TextColumn::make('converted_price')
                    ->label('Your Price')
                    ->getStateUsing(function (PhysicalCard $record): float {
                        $userCurrency = auth()->user()->currency;
                        $price = (float) $record->price_per_unit;

                        if (!$userCurrency || $userCurrency->code === $record->currency) {
                            return $price;
                        }

                        $fromCurrency = \App\Models\Currency::where('code', $record->currency)->first();

                        if (!$fromCurrency) {
                            return $price;
                        }

                        return $fromCurrency->convertTo($price, $userCurrency);
                    })
                    ->money(fn (): string => auth()->user()->currency_code ?? 'USD')
                    ->weight('bold')
                    ->color('success')
                    ->description(fn (): string => "In your currency"),

                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Available')
                    ->getStateUsing(function (PhysicalCard $record): int {
                        $cart = auth()->user()->cart;
                        return CartItem::getAvailableQuantity($record->id, $cart?->id);
                    })
                    ->badge()
                    ->color(fn (int $state): string => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->description(fn (PhysicalCard $record): string => "Total stock: {$record->quantity}"),

                Tables\Columns\TextColumn::make('language')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('tradeable')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Seller')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'mint' => 'Mint',
                        'near_mint' => 'Near Mint',
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'played' => 'Played',
                    ]),

                Tables\Filters\SelectFilter::make('language')
                    ->options(fn (): array => PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
                        ->distinct()
                        ->pluck('language', 'language')
                        ->filter()
                        ->toArray()),

                Tables\Filters\SelectFilter::make('set')
                    ->options(fn (): array => PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
                        ->distinct()
                        ->pluck('set', 'set')
                        ->filter()
                        ->toArray())
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('tradeable')
                    ->label('Tradeable Only')
                    ->boolean()
                    ->trueLabel('Tradeable')
                    ->falseLabel('Not Tradeable')
                    ->queries(
                        true: fn (Builder $query) => $query->where('tradeable', true),
                        false: fn (Builder $query) => $query->where('tradeable', false),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->slideOver()
                    ->modalWidth('4xl')
                    ->infolist(fn (PhysicalCard $record): array => [
                        Components\ImageEntry::make('image')
                            ->disk('public')
                            ->hiddenLabel()
                            ->height(400)
                            ->columnSpanFull(),

                        Components\Split::make([
                            Components\Section::make('Card Details')
                                ->schema([
                                    Components\TextEntry::make('title')
                                        ->size('lg')
                                        ->weight('bold'),

                                    Components\TextEntry::make('description')
                                        ->markdown()
                                        ->hidden(fn ($state): bool => empty($state)),

                                    Components\Grid::make(2)
                                        ->schema([
                                            Components\TextEntry::make('price_per_unit')
                                                ->label('Price')
                                                ->money($record->currency)
                                                ->size('lg')
                                                ->weight('bold')
                                                ->color('success'),

                                            Components\TextEntry::make('quantity')
                                                ->label('Available Stock')
                                                ->badge()
                                                ->color(fn (int $state): string => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                                        ]),

                                    Components\Grid::make(2)
                                        ->schema([
                                            Components\TextEntry::make('set')
                                                ->hidden(fn ($state): bool => empty($state)),
                                            Components\TextEntry::make('edition')
                                                ->hidden(fn ($state): bool => empty($state)),
                                            Components\TextEntry::make('language')
                                                ->hidden(fn ($state): bool => empty($state)),
                                            Components\TextEntry::make('condition')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'mint' => 'success',
                                                    'near_mint' => 'info',
                                                    'excellent' => 'primary',
                                                    'good' => 'warning',
                                                    'played' => 'gray',
                                                    default => 'gray',
                                                }),
                                        ]),

                                    Components\TextEntry::make('tradeable')
                                        ->label('Available for Trade')
                                        ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                                        ->badge()
                                        ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                                ])
                                ->grow(true),

                            Components\Section::make('Seller Information')
                                ->schema([
                                    Components\TextEntry::make('user.name')
                                        ->label('Seller'),

                                    Components\TextEntry::make('user.email')
                                        ->label('Email')
                                        ->copyable(),

                                    Components\TextEntry::make('user.seller_location')
                                        ->label('Location')
                                        ->icon('heroicon-o-map-pin')
                                        ->hidden(fn ($state): bool => empty($state)),

                                    Components\TextEntry::make('user.shipping_price')
                                        ->label('Shipping Cost')
                                        ->money(fn (PhysicalCard $record): string => $record->user->shipping_currency ?? 'USD')
                                        ->hidden(fn ($state): bool => empty($state)),

                                    Components\TextEntry::make('user.delivery_time')
                                        ->label('Delivery Time')
                                        ->hidden(fn ($state): bool => empty($state)),
                                ])
                                ->grow(false),
                        ]),
                    ])
                    ->extraModalFooterActions(fn (PhysicalCard $record): array => [
                        Tables\Actions\Action::make('addToCart')
                            ->label('Add to Cart')
                            ->icon('heroicon-o-shopping-cart')
                            ->color('primary')
                            ->visible(fn (PhysicalCard $record): bool => $record->user_id !== auth()->id())
                            ->form([
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(fn (PhysicalCard $record): int => $record->quantity)
                                    ->required(),
                            ])
                            ->action(function (PhysicalCard $record, array $data): void {
                                $this->addToCartAction($record->id, (int) $data['quantity']);
                            }),
                    ]),

                Tables\Actions\Action::make('addToCart')
                    ->label('Cart')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->visible(fn (PhysicalCard $record): bool => $record->user_id !== auth()->id())
                    ->form([
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(fn (PhysicalCard $record): int => $record->quantity)
                            ->required(),
                    ])
                    ->action(function (PhysicalCard $record, array $data): void {
                        $this->addToCartAction($record->id, (int) $data['quantity']);
                    }),

                Tables\Actions\EditAction::make()
                    ->visible(fn (PhysicalCard $record): bool => $record->user_id === auth()->id())
                    ->url(fn (PhysicalCard $record): string => route('filament.admin.resources.physical-cards.edit', ['record' => $record])),
            ])
            ->defaultSort('approved_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }

    protected function addToCartAction(int $cardId, int $quantity = 1): void
    {
        $user = auth()->user();
        $physicalCard = PhysicalCard::findOrFail($cardId);

        // Prevent users from adding their own cards to cart
        if ($physicalCard->user_id === $user->id) {
            Notification::make()
                ->danger()
                ->title('Cannot add to cart')
                ->body('You cannot add your own cards to the cart')
                ->send();
            return;
        }

        // Check if card is approved/published
        if (!$physicalCard->isApproved() && !$physicalCard->isPublished()) {
            Notification::make()
                ->danger()
                ->title('Cannot add to cart')
                ->body('This card is not available for purchase')
                ->send();
            return;
        }

        $cart = $user->getOrCreateCart();

        // Check available quantity (stock - reserved by others)
        $availableQuantity = CartItem::getAvailableQuantity($cardId, $cart->id);

        // Check if item already exists in user's cart
        $cartItem = $cart->items()->where('physical_card_id', $cardId)->first();

        if ($cartItem) {
            // Calculate new total quantity
            $newQuantity = $cartItem->quantity + $quantity;

            // Validate against available quantity (not including current user's reservation)
            if ($quantity > $availableQuantity) {
                Notification::make()
                    ->danger()
                    ->title('Not available')
                    ->body("Only {$availableQuantity} available. The remaining stock is reserved by other customers.")
                    ->send();
                return;
            }

            if ($newQuantity > $physicalCard->quantity) {
                Notification::make()
                    ->warning()
                    ->title('Insufficient stock')
                    ->body("You already have {$cartItem->quantity} in cart. Total would exceed available stock.")
                    ->send();
                return;
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->extendReservation();

            Notification::make()
                ->success()
                ->title('Cart updated')
                ->body("Quantity updated to {$newQuantity}")
                ->send();
        } else {
            // Validate requested quantity against available quantity
            if ($quantity > $availableQuantity) {
                Notification::make()
                    ->danger()
                    ->title('Not available')
                    ->body($availableQuantity > 0
                        ? "Only {$availableQuantity} available. The remaining stock is reserved by other customers."
                        : "This item is currently reserved by other customers. Please try again later.")
                    ->send();
                return;
            }

            // Create new cart item with automatic reservation
            CartItem::create([
                'cart_id' => $cart->id,
                'physical_card_id' => $cardId,
                'quantity' => $quantity,
            ]);

            Notification::make()
                ->success()
                ->title('Added to cart')
                ->body("{$physicalCard->title} has been added to your cart (reserved for 1 hour)")
                ->send();
        }

        // Refresh the page to show updated cart count
        $this->dispatch('cart-updated');
    }
}
