<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\CardAuditLogResource\Pages;
use App\Models\CardStatusHistory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CardAuditLogResource extends Resource
{
    protected static ?string $model = CardStatusHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?string $navigationLabel = 'Card Audit Log';

    protected static ?int $navigationSort = 20;

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

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('physical_card.title')
                    ->label('Card')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->url(fn ($record) => $record->physical_card_id ? route('filament.admin.resources.physical-cards.edit', $record->physical_card_id) : null),

                Tables\Columns\TextColumn::make('action_type')
                    ->label('Action')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'card_created' => 'Created',
                        'auto_evaluation' => 'Auto Evaluated',
                        'supervisor_approval' => 'Approved',
                        'supervisor_rejection' => 'Rejected',
                        'user_edit' => 'User Edit',
                        'user_resubmission' => 'Re-submitted',
                        default => ucwords(str_replace('_', ' ', $state)),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'card_created' => 'gray',
                        'auto_evaluation' => 'info',
                        'supervisor_approval' => 'success',
                        'supervisor_rejection' => 'danger',
                        'user_edit' => 'warning',
                        'user_resubmission' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('old_status')
                    ->label('From')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : 'N/A')
                    ->color('gray'),

                Tables\Columns\IconColumn::make('status_change')
                    ->label('')
                    ->icon('heroicon-o-arrow-right')
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('new_status')
                    ->label('To')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'pending_auto' => 'warning',
                        'under_review' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'published' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Changed By')
                    ->formatStateUsing(fn (?string $state, $record): string =>
                        $record->user_id === 1 ? 'System' : ($state ?? 'Unknown')
                    )
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('Y-m-d H:i:s')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action_type')
                    ->label('Action Type')
                    ->options([
                        'card_created' => 'Created',
                        'auto_evaluation' => 'Auto Evaluated',
                        'supervisor_approval' => 'Supervisor Approval',
                        'supervisor_rejection' => 'Supervisor Rejection',
                        'user_edit' => 'User Edit',
                        'user_resubmission' => 'Re-submitted',
                    ]),

                Tables\Filters\SelectFilter::make('new_status')
                    ->label('New Status')
                    ->options([
                        'pending_auto' => 'Pending Auto',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'published' => 'Published',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Audit Log Details')
                    ->modalContent(fn ($record) => view('filament.resources.card-audit-log-view', ['record' => $record]))
                    ->modalWidth('2xl'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCardAuditLogs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }
}
