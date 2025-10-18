<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteSettingResource\Pages;
use App\Models\WebsiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteSettingResource extends Resource
{
    protected static ?string $model = WebsiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?int $navigationSort = 99;

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting Details')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Unique identifier for this setting (cannot be changed after creation)'),

                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('group')
                            ->required()
                            ->options([
                                'general' => 'General',
                                'seo' => 'SEO',
                                'analytics' => 'Analytics & Tracking',
                                'social' => 'Social Media',
                                'branding' => 'Branding',
                            ])
                            ->default('general'),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'boolean' => 'Boolean (Yes/No)',
                                'number' => 'Number',
                                'email' => 'Email',
                                'url' => 'URL',
                            ])
                            ->default('text')
                            ->live(),

                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Order of appearance in the list'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Setting Value')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->hidden(fn (Forms\Get $get) => !in_array($get('type'), ['text', 'email', 'url', 'number']))
                            ->required(fn (Forms\Get $get) => in_array($get('type'), ['text', 'email', 'url', 'number']))
                            ->email(fn (Forms\Get $get) => $get('type') === 'email')
                            ->url(fn (Forms\Get $get) => $get('type') === 'url')
                            ->numeric(fn (Forms\Get $get) => $get('type') === 'number'),

                        Forms\Components\Textarea::make('value_textarea')
                            ->label('Value')
                            ->rows(4)
                            ->hidden(fn (Forms\Get $get) => $get('type') !== 'textarea')
                            ->required(fn (Forms\Get $get) => $get('type') === 'textarea')
                            ->afterStateHydrated(function (Forms\Components\Textarea $component, $state, $record) {
                                if ($record && $record->type === 'textarea') {
                                    $component->state($record->getRawOriginal('value'));
                                }
                            })
                            ->dehydrated(false),

                        Forms\Components\Toggle::make('value_boolean')
                            ->label('Value')
                            ->hidden(fn (Forms\Get $get) => $get('type') !== 'boolean')
                            ->inline(false)
                            ->afterStateHydrated(function (Forms\Components\Toggle $component, $state, $record) {
                                if ($record && $record->type === 'boolean') {
                                    $component->state((bool) $record->getRawOriginal('value'));
                                }
                            })
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('key')
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'seo' => 'success',
                        'analytics' => 'info',
                        'social' => 'warning',
                        'branding' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->type === 'boolean'
                            ? ($state ? 'Yes' : 'No')
                            : $state
                    ),

                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'seo' => 'SEO',
                        'analytics' => 'Analytics & Tracking',
                        'social' => 'Social Media',
                        'branding' => 'Branding',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'boolean' => 'Boolean',
                        'number' => 'Number',
                        'email' => 'Email',
                        'url' => 'URL',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteSettings::route('/'),
            'create' => Pages\CreateWebsiteSetting::route('/create'),
            'edit' => Pages\EditWebsiteSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
