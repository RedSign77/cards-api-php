<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyResource\Pages;
use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Currencies';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Currency::active()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Currency Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Currency Code')
                            ->required()
                            ->maxLength(3)
                            ->placeholder('USD')
                            ->helperText('3-letter ISO currency code')
                            ->rule('uppercase')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('name')
                            ->label('Currency Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('US Dollar')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('symbol')
                            ->label('Currency Symbol')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('$')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->label('Exchange Rate')
                            ->required()
                            ->numeric()
                            ->default(1.000000)
                            ->step(0.000001)
                            ->minValue(0.000001)
                            ->helperText('Exchange rate relative to the base currency')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable this currency for use in the system')
                            ->inline(false),

                        Forms\Components\Toggle::make('is_base')
                            ->label('Base Currency')
                            ->default(false)
                            ->helperText('Set as the base currency for exchange rate calculations. Only one currency can be the base.')
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Currency $record): string => $record->symbol),

                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label('Exchange Rate')
                    ->numeric(decimalPlaces: 6)
                    ->sortable()
                    ->description(fn (Currency $record): string => $record->is_base ? 'Base Currency' : ''),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_base')
                    ->label('Base')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->queries(
                        true: fn ($query) => $query->where('is_active', true),
                        false: fn ($query) => $query->where('is_active', false),
                        blank: fn ($query) => $query,
                    ),

                Tables\Filters\TernaryFilter::make('is_base')
                    ->label('Base Currency')
                    ->boolean()
                    ->trueLabel('Base currency')
                    ->falseLabel('Not base')
                    ->queries(
                        true: fn ($query) => $query->where('is_base', true),
                        false: fn ($query) => $query->where('is_base', false),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Currency')
                    ->modalDescription('Are you sure you want to delete this currency? This action cannot be undone.')
                    ->before(function (Currency $record) {
                        if ($record->is_base) {
                            throw new \Exception('Cannot delete the base currency. Please set another currency as base first.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->is_base) {
                                    throw new \Exception('Cannot delete the base currency. Please set another currency as base first.');
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->emptyStateHeading('No currencies configured')
            ->emptyStateDescription('Add your first currency to enable multi-currency support')
            ->emptyStateIcon('heroicon-o-currency-dollar');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
