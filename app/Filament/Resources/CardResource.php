<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Filament\Resources\CardResource\RelationManagers;
use App\Models\Card;
use App\Models\CardType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Game Content';

    protected static ?string $navigationLabel = 'Cards';

    protected static ?string $modelLabel = 'Cards';

    protected static ?string $pluralModelLabel = 'Cards';

    protected static ?int $navigationSort = 30;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Base Data')
                    ->schema([
                        Forms\Components\Select::make('game_id')
                            ->label('Game')
                            ->options(fn () => Game::where('creator_id', auth()->id())->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('type_id')
                            ->label('Type')
                            ->options(fn () => CardType::where('user_id', auth()->id())->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->directory('images')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('card_text')
                            ->label('Text')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                ->columns(2),
                Forms\Components\Section::make('Card Fields')
                    ->description('Dynamic fields on the card')
                    ->schema([
                        Forms\Components\Repeater::make('card_data')
                            ->label('Fields')
                            ->schema([
                                Forms\Components\TextInput::make('fieldname')
                                    ->label('Name')
                                    ->required()
                                    ->placeholder('Samples: Strength, Health, etc.')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('fieldvalue')
                                    ->label('Value')
                                    ->required()
                                    ->placeholder('Samples: 5, +2, Damage reduction')
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add new field')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['fieldname'] ?? 'New field')
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(url('/images/placeholder-card.svg')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CardType.name')
                    ->label('Type')
                    ->sortable()
                    ->color('primary')
                    ->badge(),
                Tables\Columns\TextColumn::make('card_data')
                    ->label('Number of fields')
                    ->getStateUsing(fn ($record) => is_array($record->card_data) ? count($record->card_data) : 0)
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('game_id')
                    ->label('Game')
                    ->relationship('game', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Type')
                    ->relationship('cardType', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => $record->name)
                    ->modalContent(fn ($record) => view('filament.resources.card-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'cards-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('id'),
                                Column::make('name'),
                                Column::make('game.name')->heading('Game'),
                                Column::make('CardType.name')->heading('Type'),
                                Column::make('card_text')->heading('Text'),
                                Column::make('card_data')->formatStateUsing(fn ($state) => json_encode($state)),
                                Column::make('created_at'),
                                Column::make('updated_at'),
                            ])
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('addToDeck')
                        ->label('Add to Deck')
                        ->icon('heroicon-o-rectangle-stack')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('deck_id')
                                ->label('Select Deck')
                                ->options(function () {
                                    return \App\Models\Deck::where('creator_id', auth()->id())
                                        ->get()
                                        ->mapWithKeys(function ($deck) {
                                            return [$deck->id => $deck->deck_name . ' (' . $deck->game->name . ')'];
                                        });
                                })
                                ->required()
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (array $data, $records) {
                            $deck = \App\Models\Deck::find($data['deck_id']);
                            $cardIds = $records->pluck('id')->toArray();

                            $deck->addCards($cardIds);

                            \Filament\Notifications\Notification::make()
                                ->title('Cards added to deck')
                                ->success()
                                ->body(count($cardIds) . ' card(s) added to "' . $deck->deck_name . '"')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'cards-' . date('Y-m-d'))
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())->count();
    }
}
