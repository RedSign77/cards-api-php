<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\PhysicalCardResource\Pages;
use App\Models\PhysicalCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class PhysicalCardResource extends Resource
{
    protected static ?string $model = PhysicalCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?string $navigationLabel = 'My Cards';

    protected static ?int $navigationSort = 1;

    protected static ?int $navigationGroupSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())->count();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Card Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Card Image')
                            ->image()
                            ->imageEditor()
                            ->directory('physical-cards')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('set')
                            ->maxLength(255)
                            ->placeholder('e.g., Base Set, Expansion 1'),

                        Forms\Components\TextInput::make('edition')
                            ->maxLength(255)
                            ->placeholder('e.g., 1st Edition, Unlimited'),

                        Forms\Components\Select::make('language')
                            ->options([
                                'English' => 'English',
                                'Spanish' => 'Spanish',
                                'French' => 'French',
                                'German' => 'German',
                                'Italian' => 'Italian',
                                'Portuguese' => 'Portuguese',
                                'Japanese' => 'Japanese',
                                'Chinese' => 'Chinese',
                                'Korean' => 'Korean',
                                'Other' => 'Other',
                            ])
                            ->default('English')
                            ->required(),

                        Forms\Components\Select::make('condition')
                            ->options([
                                'Mint' => 'Mint',
                                'Near Mint' => 'Near Mint',
                                'Excellent' => 'Excellent',
                                'Good' => 'Good',
                                'Light Played' => 'Light Played',
                                'Played' => 'Played',
                                'Poor' => 'Poor',
                            ])
                            ->default('Near Mint')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->rows(4)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Inventory & Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),

                        Forms\Components\TextInput::make('price_per_unit')
                            ->label('Price per Unit')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),

                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'JPY' => 'JPY - Japanese Yen',
                                'CAD' => 'CAD - Canadian Dollar',
                                'AUD' => 'AUD - Australian Dollar',
                            ])
                            ->default('USD')
                            ->required(),

                        Forms\Components\Toggle::make('tradeable')
                            ->label('Available for Trade')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Card Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label('Card Image')
                            ->defaultImageUrl(url('/images/placeholder-card.png'))
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('title')
                            ->label('Title')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('set')
                            ->label('Set'),

                        Infolists\Components\TextEntry::make('edition')
                            ->label('Edition'),

                        Infolists\Components\TextEntry::make('language')
                            ->label('Language')
                            ->badge(),

                        Infolists\Components\TextEntry::make('condition')
                            ->label('Condition')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Mint' => 'success',
                                'Near Mint' => 'success',
                                'Excellent' => 'info',
                                'Good' => 'warning',
                                'Light Played' => 'warning',
                                'Played' => 'danger',
                                'Poor' => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Inventory & Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('quantity')
                            ->label('Quantity'),

                        Infolists\Components\TextEntry::make('price_per_unit')
                            ->label('Price per Unit')
                            ->money(fn ($record) => $record->currency),

                        Infolists\Components\TextEntry::make('currency')
                            ->label('Currency'),

                        Infolists\Components\IconEntry::make('tradeable')
                            ->label('Available for Trade')
                            ->boolean(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('Y-m-d H:i:s'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('Y-m-d H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-card.png')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('set')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('edition')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('language')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mint' => 'success',
                        'Near Mint' => 'success',
                        'Excellent' => 'info',
                        'Good' => 'warning',
                        'Light Played' => 'warning',
                        'Played' => 'danger',
                        'Poor' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Price')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\IconColumn::make('tradeable')
                    ->label('Trade')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'English' => 'English',
                        'Spanish' => 'Spanish',
                        'French' => 'French',
                        'German' => 'German',
                        'Italian' => 'Italian',
                        'Portuguese' => 'Portuguese',
                        'Japanese' => 'Japanese',
                        'Chinese' => 'Chinese',
                        'Korean' => 'Korean',
                        'Other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'Mint' => 'Mint',
                        'Near Mint' => 'Near Mint',
                        'Excellent' => 'Excellent',
                        'Good' => 'Good',
                        'Light Played' => 'Light Played',
                        'Played' => 'Played',
                        'Poor' => 'Poor',
                    ]),
                Tables\Filters\TernaryFilter::make('tradeable')
                    ->label('Available for Trade')
                    ->boolean()
                    ->trueLabel('Available')
                    ->falseLabel('Not Available'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => $record->title)
                    ->modalContent(fn ($record) => view('filament.resources.physical-card-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhysicalCards::route('/'),
            'create' => Pages\CreatePhysicalCard::route('/create'),
            'edit' => Pages\EditPhysicalCard::route('/{record}/edit'),
        ];
    }
}
