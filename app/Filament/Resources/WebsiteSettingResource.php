<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteSettingResource\Pages;
use App\Filament\Resources\WebsiteSettingResource\RelationManagers;
use App\Models\WebsiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteSettingResource extends Resource
{
    protected static ?string $model = WebsiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 20;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting Information')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Label')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Unique identifier for this setting')
                            ->columnSpan(1),

                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->required()
                            ->options([
                                'text' => 'Text',
                                'number' => 'Number',
                                'boolean' => 'Boolean',
                                'textarea' => 'Long Text',
                            ])
                            ->default('text')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->required()
                            ->helperText('The actual value of this setting')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->helperText('Explain what this setting does')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('group')
                            ->label('Group')
                            ->required()
                            ->options([
                                'general' => 'General',
                                'logs' => 'Logs & Cleanup',
                                'email' => 'Email',
                                'security' => 'Security',
                                'maintenance' => 'Maintenance',
                            ])
                            ->default('general')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Setting')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (WebsiteSetting $record): string => $record->key),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->limit(50),

                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'logs' => 'warning',
                        'security' => 'danger',
                        'maintenance' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'logs' => 'Logs & Cleanup',
                        'email' => 'Email',
                        'security' => 'Security',
                        'maintenance' => 'Maintenance',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'textarea' => 'Long Text',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('group')
            ->defaultGroup('group')
            ->reorderable('order')
            ->emptyStateHeading('No settings configured')
            ->emptyStateDescription('Add your first website setting')
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
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
            'index' => Pages\ListWebsiteSettings::route('/'),
            'create' => Pages\CreateWebsiteSetting::route('/create'),
            'edit' => Pages\EditWebsiteSetting::route('/{record}/edit'),
        ];
    }
}
