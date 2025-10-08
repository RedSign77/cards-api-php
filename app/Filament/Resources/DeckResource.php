<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeckResource\Pages;
use App\Filament\Resources\DeckResource\RelationManagers;
use App\Models\Card;
use App\Models\Deck;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class DeckResource extends Resource
{
    protected static ?string $model = Deck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'Decks';

    protected static ?string $modelLabel = 'Deck';

    protected static ?string $pluralModelLabel = 'Decks';

    protected static ?string $navigationGroup = 'Game Content';

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('creator_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Data')
                    ->description('Card Types basic configuration data')
                    ->schema([
                        Forms\Components\Select::make('game_id')
                            ->label('Game')
                            ->options(fn () => Game::where('creator_id', auth()->id())->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
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
                                    ->options(fn () => Card::where('user_id', auth()->id())->pluck('name', 'id'))
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
                Tables\Columns\TextColumn::make('total_cards')
                    ->label('Total Cards')
                    ->getStateUsing(function ($record) {
                        $deckData = $record->deck_data;

                        // Decode if it's a JSON string
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'decks-' . date('Y-m-d') . '.csv')
                            ->withWriterType(\Maatwebsite\Excel\Excel::CSV)
                            ->withColumns([
                                Column::make('deck_name'),
                                Column::make('game_id'),
                                Column::make('game.name')->heading('Game Name'),
                                Column::make('deck_description'),
                                Column::make('deck_data')->formatStateUsing(fn ($state) => json_encode($state)),
                            ])
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'decks-' . date('Y-m-d') . '.csv')
                                ->withWriterType(\Maatwebsite\Excel\Excel::CSV)
                                ->withColumns([
                                    Column::make('deck_name'),
                                    Column::make('game_id'),
                                    Column::make('game.name')->heading('Game Name'),
                                    Column::make('deck_description'),
                                    Column::make('deck_data')->formatStateUsing(fn ($state) => json_encode($state)),
                                ])
                        ]),
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
        return static::getModel()::where('creator_id', auth()->id())->count();
    }
}
