<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Models\CardStatusHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentApprovalsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Approvals & Rejections';

    public static function canView(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CardStatusHistory::query()
                    ->whereIn('action_type', ['supervisor_approval', 'supervisor_rejection', 'auto_evaluation'])
                    ->with(['physicalCard', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('When')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('physicalCard.title')
                    ->label('Card')
                    ->limit(30)
                    ->searchable()
                    ->url(fn ($record) => $record->physical_card_id ? route('filament.admin.resources.physical-cards.edit', $record->physical_card_id) : null),

                Tables\Columns\TextColumn::make('action_type')
                    ->label('Action')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'supervisor_approval' => 'Approved',
                        'supervisor_rejection' => 'Rejected',
                        'auto_evaluation' => 'Auto-Approved',
                        default => ucwords(str_replace('_', ' ', $state)),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'supervisor_approval' => 'success',
                        'supervisor_rejection' => 'danger',
                        'auto_evaluation' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('new_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'under_review' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('By')
                    ->formatStateUsing(fn (?string $state, $record): string =>
                        $record->user_id === 1 ? 'System' : ($state ?? 'Unknown')
                    ),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->toggleable()
                    ->tooltip(fn (?string $state): ?string => $state),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10]);
    }
}
