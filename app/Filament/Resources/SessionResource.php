<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\SessionResource\Pages;
use App\Models\Session;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SessionResource extends Resource
{
    protected static ?string $model = Session::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 15;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Session Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Session ID')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User')
                            ->default('Guest'),
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('IP Address')
                            ->formatStateUsing(fn ($state) => new HtmlString(
                                $state
                                    ? '<a href="https://whatismyipaddress.com/ip/' . e($state) . '" target="_blank" class="text-primary-600 hover:underline">' . e($state) . '</a>'
                                    : 'N/A'
                            )),
                        Infolists\Components\TextEntry::make('last_activity')
                            ->label('Last Activity')
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? date('Y-m-d H:i:s', (int) $state) : $state),
                        Infolists\Components\TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('payload')
                            ->label('Payload')
                            ->columnSpanFull()
                            ->limit(500),
                    ])
                    ->columns(2),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('Session ID')
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->disabled(),
                Forms\Components\TextInput::make('ip_address')
                    ->label('IP Address')
                    ->disabled(),
                Forms\Components\Textarea::make('user_agent')
                    ->label('User Agent')
                    ->disabled(),
                Forms\Components\Textarea::make('payload')
                    ->label('Payload')
                    ->disabled()
                    ->rows(5),
                Forms\Components\TextInput::make('last_activity')
                    ->label('Last Activity')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('Guest'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => new HtmlString(
                        $state
                            ? '<a href="https://whatismyipaddress.com/ip/' . e($state) . '" target="_blank" class="text-primary-600 hover:underline">' . e($state) . '</a>'
                            : 'N/A'
                    )),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => is_numeric($state) ? date('Y-m-d H:i:s', (int) $state) : $state),
            ])
            ->defaultSort('last_activity', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Session Details')
                    ->modalContent(fn ($record) => view('filament.resources.session-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
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
            'index' => Pages\ListSessions::route('/'),
        ];
    }
}
