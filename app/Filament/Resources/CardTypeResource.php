<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardTypeResource\Pages;
use App\Models\CardType;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardTypeResource extends Resource
{
    protected static ?string $model = CardType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Card Types';

    protected static ?string $modelLabel = 'Card Type';

    protected static ?string $pluralModelLabel = 'Card Types';

    protected static ?string $navigationGroup = 'Game Content';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Data')
                    ->description('Card Types basic configuration data')
                    ->schema([
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
                                    ->default(fn () => auth()->id()),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('examples: Mini Potter, King Leech, Tree of Sultans')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, $get) {
                                if (empty($get('typetext'))) {
                                    $set('typetext', strtolower(str_replace(' ', '_', $state)));
                                }
                            }),

                        Forms\Components\TextInput::make('typetext')
                            ->label('Text')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('examples: character, item, spell')
                            ->alphaDash()
                            ->unique(CardType::class, 'typetext', ignoreRecord: true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('typetext')
                    ->label('Identifier')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Identifier copied!')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('cards_count')
                    ->label('Number of Cards')
                    ->counts('cards')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === 0 => 'gray',
                        $state < 5 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->html()
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('game_id')
                    ->label('Game')
                    ->relationship('game', 'name')
                    ->preload()
                    ->multiple(),

                Tables\Filters\Filter::make('has_cards')
                    ->label('Has cards')
                    ->query(fn (Builder $query): Builder => $query->has('cards')),

                Tables\Filters\Filter::make('no_cards')
                    ->label('Doesn\'t have cards')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('cards')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),

                Tables\Actions\EditAction::make()
                    ->slideOver(),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this type? The cards associated with it will also be deleted!'),

                Tables\Actions\Action::make('viewCards')
                    ->label('Cards')
                    ->icon('heroicon-o-credit-card')
                    ->color('info')
                    ->url(fn (CardType $record): string =>
                    route('filament.admin.resources.cards.index', [
                        'tableFilters' => [
                            'type_id' => ['value' => $record->id]
                        ]
                    ])
                    )
                    ->visible(fn (CardType $record): bool => $record->cards_count > 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession();
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
            'index' => Pages\ListCardTypes::route('/'),
            'create' => Pages\CreateCardType::route('/create'),
            'edit' => Pages\EditCardType::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        return match (true) {
            $count === 0 => 'gray',
            $count < 5 => 'warning',
            default => 'success',
        };
    }
}
