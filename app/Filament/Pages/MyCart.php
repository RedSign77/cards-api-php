<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\CartItem;
use App\Models\PhysicalCard;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
        $userCurrency = auth()->user()->currency;
        $currencyCode = $userCurrency ? $userCurrency->code : 'USD';
        $currencySymbol = $userCurrency ? $userCurrency->symbol : '$';

        // Group items by seller and calculate totals
        $sellerGroups = [];
        $itemsTotal = 0;

        foreach ($cart->items as $item) {
            $sellerId = $item->physicalCard->user_id;
            $price = (float) $item->physicalCard->price_per_unit;
            $quantity = $item->quantity;

            // Convert item price
            if ($userCurrency && $userCurrency->code !== $item->physicalCard->currency) {
                $fromCurrency = \App\Models\Currency::where('code', $item->physicalCard->currency)->first();
                if ($fromCurrency) {
                    $price = $fromCurrency->convertTo($price, $userCurrency);
                }
            }

            $itemSubtotal = $price * $quantity;
            $itemsTotal += $itemSubtotal;

            // Initialize seller group if not exists
            if (!isset($sellerGroups[$sellerId])) {
                $seller = $item->physicalCard->user;
                $shippingPrice = (float) ($seller->shipping_price ?? 0);

                // Convert shipping price
                if ($shippingPrice > 0 && $userCurrency && $userCurrency->code !== ($seller->shipping_currency ?? 'USD')) {
                    $fromCurrency = \App\Models\Currency::where('code', $seller->shipping_currency ?? 'USD')->first();
                    if ($fromCurrency) {
                        $shippingPrice = $fromCurrency->convertTo($shippingPrice, $userCurrency);
                    }
                }

                $sellerGroups[$sellerId] = [
                    'name' => $seller->name,
                    'shipping' => $shippingPrice,
                    'items' => 0,
                ];
            }

            $sellerGroups[$sellerId]['items']++;
        }

        // Calculate total shipping
        $totalShipping = array_sum(array_column($sellerGroups, 'shipping'));
        $grandTotal = $itemsTotal + $totalShipping;

        // Build summary
        $sellerCount = count($sellerGroups);
        $summary = "You have {$totalItems} item(s) from {$sellerCount} seller(s)";

        // Add seller breakdown if multiple sellers
        if ($sellerCount > 1) {
            $summary .= " • Items: {$currencySymbol}" . number_format($itemsTotal, 2);
            $summary .= " + Shipping: {$currencySymbol}" . number_format($totalShipping, 2);
            $summary .= " = Total: {$currencySymbol}" . number_format($grandTotal, 2) . " {$currencyCode}";
        } else {
            $summary .= " • Total: {$currencySymbol}" . number_format($grandTotal, 2) . " {$currencyCode}";
            if ($totalShipping > 0) {
                $summary .= " (inc. {$currencySymbol}" . number_format($totalShipping, 2) . " shipping)";
            }
        }

        return $summary;
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

    public function mount(): void
    {
        // Extend all reservations in the user's cart when they visit the cart page
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->items->each(fn ($item) => $item->extendReservation());
        }
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
                    ->label('Original Price')
                    ->money(fn (CartItem $record): string => $record->physicalCard->currency)
                    ->description(fn (CartItem $record): string => "Seller's currency"),

                Tables\Columns\TextColumn::make('converted_price')
                    ->label('Your Price')
                    ->getStateUsing(function (CartItem $record): float {
                        $userCurrency = auth()->user()->currency;
                        $price = (float) $record->physicalCard->price_per_unit;

                        if (!$userCurrency || $userCurrency->code === $record->physicalCard->currency) {
                            return $price;
                        }

                        $fromCurrency = \App\Models\Currency::where('code', $record->physicalCard->currency)->first();

                        if (!$fromCurrency) {
                            return $price;
                        }

                        return $fromCurrency->convertTo($price, $userCurrency);
                    })
                    ->money(fn (): string => auth()->user()->currency_code ?? 'USD')
                    ->weight('bold')
                    ->color('success')
                    ->description(fn (): string => "In your currency"),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(function (CartItem $record): float {
                        $userCurrency = auth()->user()->currency;
                        $price = (float) $record->physicalCard->price_per_unit;
                        $quantity = $record->quantity;

                        if (!$userCurrency || $userCurrency->code === $record->physicalCard->currency) {
                            return $price * $quantity;
                        }

                        $fromCurrency = \App\Models\Currency::where('code', $record->physicalCard->currency)->first();

                        if (!$fromCurrency) {
                            return $price * $quantity;
                        }

                        $convertedPrice = $fromCurrency->convertTo($price, $userCurrency);
                        return $convertedPrice * $quantity;
                    })
                    ->money(fn (): string => auth()->user()->currency_code ?? 'USD')
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('shipping')
                    ->label('Shipping')
                    ->getStateUsing(function (CartItem $record): float {
                        $seller = $record->physicalCard->user;
                        $userCurrency = auth()->user()->currency;

                        if (!$seller->shipping_price) {
                            return 0;
                        }

                        $shippingPrice = (float) $seller->shipping_price;
                        $shippingCurrency = $seller->shipping_currency ?? 'USD';

                        if (!$userCurrency || $userCurrency->code === $shippingCurrency) {
                            return $shippingPrice;
                        }

                        $fromCurrency = \App\Models\Currency::where('code', $shippingCurrency)->first();

                        if (!$fromCurrency) {
                            return $shippingPrice;
                        }

                        return $fromCurrency->convertTo($shippingPrice, $userCurrency);
                    })
                    ->money(fn (): string => auth()->user()->currency_code ?? 'USD')
                    ->color('info')
                    ->description(fn (CartItem $record): string =>
                        $record->physicalCard->user->shipping_price ? "Per seller" : "Free"
                    ),
            ])
            ->headerActions([
                Tables\Actions\Action::make('checkout')
                    ->label('Proceed to Checkout')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('success')
                    ->size('lg')
                    ->visible(fn (): bool => auth()->user()->cart?->items->isNotEmpty() ?? false)
                    ->form([
                        Section::make('Shipping Address')
                            ->schema([
                                TextInput::make('shipping_name')
                                    ->label('Full Name')
                                    ->default(fn (): string => auth()->user()->name)
                                    ->required()
                                    ->maxLength(255),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('shipping_address_line1')
                                            ->label('Address Line 1')
                                            ->default(fn (): ?string => auth()->user()->shipping_address_line1)
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('shipping_address_line2')
                                            ->label('Address Line 2')
                                            ->default(fn (): ?string => auth()->user()->shipping_address_line2)
                                            ->maxLength(255),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('shipping_city')
                                            ->label('City')
                                            ->default(fn (): ?string => auth()->user()->shipping_city)
                                            ->required()
                                            ->maxLength(100),

                                        TextInput::make('shipping_state')
                                            ->label('State/Province')
                                            ->default(fn (): ?string => auth()->user()->shipping_state)
                                            ->maxLength(100),

                                        TextInput::make('shipping_postal_code')
                                            ->label('Postal Code')
                                            ->default(fn (): ?string => auth()->user()->shipping_postal_code)
                                            ->required()
                                            ->maxLength(20),
                                    ]),

                                TextInput::make('shipping_country')
                                    ->label('Country')
                                    ->default(fn (): ?string => auth()->user()->shipping_country)
                                    ->required()
                                    ->maxLength(100),
                            ])
                            ->columns(1),

                        Section::make('Payment Method')
                            ->schema([
                                Select::make('payment_method')
                                    ->label('Select Payment Method')
                                    ->options([
                                        'paypal' => 'PayPal',
                                        'check' => 'Check',
                                        'bank_transfer' => 'Bank Transfer',
                                    ])
                                    ->required()
                                    ->default('paypal')
                                    ->native(false)
                                    ->helperText('Payment will be coordinated directly with the seller.'),
                            ]),
                    ])
                    ->modalWidth('3xl')
                    ->modalHeading('Checkout')
                    ->modalSubmitActionLabel('Place Order')
                    ->action(function (array $data): void {
                        DB::beginTransaction();

                        try {
                            $cart = auth()->user()->cart;
                            $buyer = auth()->user();
                            $userCurrency = $buyer->currency;
                            $currencyCode = $userCurrency ? $userCurrency->code : 'USD';

                            // Group cart items by seller
                            $itemsBySeller = $cart->items->groupBy('physicalCard.user_id');

                            $ordersCreated = 0;

                            foreach ($itemsBySeller as $sellerId => $items) {
                                $seller = $items->first()->physicalCard->user;
                                $orderSubtotal = 0;
                                $shippingCost = 0;

                                // Validate all items have sufficient quantity before creating order
                                foreach ($items as $cartItem) {
                                    $physicalCard = $cartItem->physicalCard;
                                    if ($physicalCard->quantity < $cartItem->quantity) {
                                        throw new \Exception("Insufficient quantity for {$physicalCard->title}. Only {$physicalCard->quantity} available.");
                                    }
                                }

                                // Calculate shipping cost for this seller
                                if ($seller->shipping_price) {
                                    $shippingCost = (float) $seller->shipping_price;
                                    $shippingCurrency = $seller->shipping_currency ?? 'USD';

                                    // Convert shipping to user's currency
                                    if ($userCurrency && $userCurrency->code !== $shippingCurrency) {
                                        $fromCurrency = \App\Models\Currency::where('code', $shippingCurrency)->first();
                                        if ($fromCurrency) {
                                            $shippingCost = $fromCurrency->convertTo($shippingCost, $userCurrency);
                                        }
                                    }
                                }

                                // Create order
                                $order = Order::create([
                                    'order_number' => Order::generateOrderNumber(),
                                    'buyer_id' => $buyer->id,
                                    'seller_id' => $sellerId,
                                    'shipping_name' => $data['shipping_name'],
                                    'shipping_address_line1' => $data['shipping_address_line1'],
                                    'shipping_address_line2' => $data['shipping_address_line2'] ?? null,
                                    'shipping_city' => $data['shipping_city'],
                                    'shipping_state' => $data['shipping_state'] ?? null,
                                    'shipping_postal_code' => $data['shipping_postal_code'],
                                    'shipping_country' => $data['shipping_country'],
                                    'payment_method' => $data['payment_method'],
                                    'payment_status' => 'pending',
                                    'subtotal' => 0,
                                    'shipping_cost' => $shippingCost,
                                    'total' => 0,
                                    'currency' => $currencyCode,
                                    'status' => 'pending',
                                ]);

                                // Create order items
                                foreach ($items as $cartItem) {
                                    $physicalCard = $cartItem->physicalCard;
                                    $price = (float) $physicalCard->price_per_unit;

                                    // Convert price to user's currency
                                    if ($userCurrency && $userCurrency->code !== $physicalCard->currency) {
                                        $fromCurrency = \App\Models\Currency::where('code', $physicalCard->currency)->first();
                                        if ($fromCurrency) {
                                            $price = $fromCurrency->convertTo($price, $userCurrency);
                                        }
                                    }

                                    $itemSubtotal = $price * $cartItem->quantity;
                                    $orderSubtotal += $itemSubtotal;

                                    OrderItem::create([
                                        'order_id' => $order->id,
                                        'physical_card_id' => $physicalCard->id,
                                        'quantity' => $cartItem->quantity,
                                        'price_per_unit' => $price,
                                        'subtotal' => $itemSubtotal,
                                        'currency' => $currencyCode,
                                    ]);

                                    // Reduce physical card quantity
                                    $physicalCard->quantity -= $cartItem->quantity;
                                    $physicalCard->save();

                                    // Delete cart item
                                    $cartItem->delete();
                                }

                                // Update order totals
                                $order->subtotal = $orderSubtotal;
                                $order->total = $orderSubtotal + $shippingCost;
                                $order->save();

                                // Send notifications to buyer and seller
                                if (config('mail.enabled')) {
                                    $seller->notify(new \App\Notifications\OrderPlaced($order, 'seller'));
                                    $buyer->notify(new \App\Notifications\OrderPlaced($order, 'buyer'));
                                }

                                $ordersCreated++;
                            }

                            DB::commit();

                            Notification::make()
                                ->success()
                                ->title('Order(s) placed successfully!')
                                ->body("{$ordersCreated} order(s) have been placed. You can track them in My Orders.")
                                ->send();

                            // Redirect to My Orders page
                            $this->redirect(route('filament.admin.pages.my-orders'));

                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->danger()
                                ->title('Order placement failed')
                                ->body('An error occurred while placing your order. Please try again.')
                                ->send();

                            logger()->error('Order placement failed', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                        }
                    }),
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
                        $cart = auth()->user()->cart;

                        // Check available quantity including current reservation
                        $availableQuantity = CartItem::getAvailableQuantity($record->physical_card_id, $cart->id) + $record->quantity;

                        // Validate against available stock (including other reservations)
                        if ($newQuantity > $availableQuantity) {
                            Notification::make()
                                ->danger()
                                ->title('Not available')
                                ->body("Only {$availableQuantity} available. The remaining stock is reserved by other customers.")
                                ->send();
                            return;
                        }

                        if ($newQuantity > $record->physicalCard->quantity) {
                            Notification::make()
                                ->warning()
                                ->title('Insufficient stock')
                                ->body("Only {$record->physicalCard->quantity} in total stock")
                                ->send();
                            return;
                        }

                        $record->quantity = $newQuantity;
                        $record->extendReservation();

                        Notification::make()
                            ->success()
                            ->title('Quantity updated')
                            ->body("Quantity updated to {$newQuantity} (reservation extended)")
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
