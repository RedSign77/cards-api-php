<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorAllDecksResource\Pages;
use App\Models\Deck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class SupervisorAllDecksResource extends Resource
{
    protected static ?string $model = Deck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'All Decks';

    protected static ?string $modelLabel = 'Deck';

    protected static ?string $pluralModelLabel = 'All Decks';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 21;

    public static function canAccess(): bool
    {
        return auth()->user()->isSupervisor();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Data')
                    ->schema([
                        Forms\Components\Select::make('game_id')
                            ->label('Game')
                            ->relationship('game', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('creator_id')
                            ->label('Creator')
                            ->relationship('creator', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('deck_name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Section::make('Deck Description')
                            ->schema([
                                Forms\Components\RichEditor::make('deck_description')
                                    ->label('Description')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),
                    ])
                ->columns(2),
                Forms\Components\Section::make('Cards in Deck')
                    ->schema([
                        Forms\Components\Repeater::make('deck_data')
                            ->label('Cards in Deck')
                            ->schema([
                                Forms\Components\Select::make('card_id')
                                    ->label('Card')
                                    ->options(fn () => \App\Models\Card::pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add Card')
                            ->cloneable()
                            ->reorderable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deck_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_cards')
                    ->label('Total Cards')
                    ->getStateUsing(function ($record) {
                        $deckData = $record->deck_data;

                        if (is_string($deckData)) {
                            $deckData = json_decode($deckData, true);
                        }

                        if (!$deckData || !is_array($deckData) || empty($deckData)) {
                            return 0;
                        }

                        $total = 0;
                        foreach ($deckData as $item) {
                            if (is_array($item) && isset($item['quantity'])) {
                                $total += (int) $item['quantity'];
                            }
                        }

                        return $total;
                    })
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->datetime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('game_id')
                    ->label('Game')
                    ->relationship('game', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Deck Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('deck_name')
                            ->label('Deck Name')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('game.name')
                            ->label('Game'),
                        Infolists\Components\TextEntry::make('creator.name')
                            ->label('Creator'),
                        Infolists\Components\TextEntry::make('deck_description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Cards in Deck')
                    ->schema([
                        Infolists\Components\ViewEntry::make('deck_data')
                            ->label('')
                            ->view('filament.infolists.deck-cards-list'),
                    ]),

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupervisorAllDecks::route('/'),
            'view' => Pages\ViewSupervisorAllDeck::route('/{record}'),
            'edit' => Pages\EditSupervisorAllDeck::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
