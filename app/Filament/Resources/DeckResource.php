<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeckResource\Pages;
use App\Filament\Resources\DeckResource\RelationManagers;
use App\Models\Card;
use App\Models\Deck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeckResource extends Resource
{
    protected static ?string $model = Deck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'Decks';

    protected static ?string $modelLabel = 'Deck';

    protected static ?string $pluralModelLabel = 'Decks';

    protected static ?string $navigationGroup = 'Game Content';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Data')
                    ->description('Card Types basic configuration data')
                    ->schema([
                        Forms\Components\Select::make('creator_id')
                            ->label('Creator')
                            ->relationship('creator', 'name')
                            ->required()
                            ->disabled()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('creator.name')
                                    ->label('Creator Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('creator_id')
                                    ->label('Creator ID')
                                    ->required()
                                    ->numeric()
                                    ->default(fn() => auth()->id()),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Select::make('game_id')
                            ->label('Game')
                            ->relationship('game', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Game name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('creator_id')
                                    ->label('Author ID')
                                    ->required()
                                    ->numeric()
                                    ->default(fn() => auth()->id()),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('deck_name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('examples: First Deck, Special Deck 2')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                if (empty($get('typetext'))) {
                                    $set('typetext', strtolower(str_replace(' ', '_', $state)));
                                }
                            }),
                        Forms\Components\Section::make('Deck Description')
                            ->schema([
                                Forms\Components\RichEditor::make('deck_description')
                                    ->label('Description')
                                    ->placeholder('Detailed description')
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
                    ->description('There is the list of cards on this deck')
                    ->schema([
                        Forms\Components\Repeater::make('deck_data')
                            ->label('Cards in Deck')
                            ->schema([
                                Forms\Components\Select::make('card_id')
                                    ->label('Card')
                                    ->options(Card::query()->pluck('name', 'id'))
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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deck_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label('Creator')
                    ->relationship('creator', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDecks::route('/'),
            'create' => Pages\CreateDeck::route('/create'),
            'edit' => Pages\EditDeck::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
