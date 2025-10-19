<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\PendingReviewCardResource\Pages;
use App\Models\PhysicalCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class PendingReviewCardResource extends Resource
{
    protected static ?string $model = PhysicalCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Marketplace';

    protected static ?string $navigationLabel = 'Pending Reviews';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', PhysicalCard::STATUS_UNDER_REVIEW)->count();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', PhysicalCard::STATUS_UNDER_REVIEW);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Card Information')
                    ->schema([
                        Forms\Components\Placeholder::make('status_info')
                            ->label('Review Status')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                '<div class="space-y-2">' .
                                '<div><span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">' .
                                PhysicalCard::getStatuses()[$record->status] .
                                '</span>' .
                                ($record->is_critical ? ' <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 ml-2">⚠ Critical</span>' : '') .
                                '</div>' .
                                ($record->evaluation_flags && count($record->evaluation_flags) > 0 ?
                                    '<div class="text-xs text-red-600 dark:text-red-400 font-medium">Flags: ' . implode(', ', array_map(fn($f) => str_replace('_', ' ', ucwords($f, '_')), $record->evaluation_flags)) . '</div>' :
                                    '') .
                                '</div>'
                            ))
                            ->columnSpanFull(),

                        Forms\Components\Placeholder::make('user_info')
                            ->label('Seller')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                '<div class="space-y-1">' .
                                '<div class="font-medium">' . $record->user->name . '</div>' .
                                '<div class="text-xs text-gray-600 dark:text-gray-400">' . $record->user->email . '</div>' .
                                '<div class="text-xs text-gray-600 dark:text-gray-400">Total Approved: ' . PhysicalCard::where('user_id', $record->user_id)->where('status', PhysicalCard::STATUS_APPROVED)->count() . '</div>' .
                                '</div>'
                            ))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Card Image')
                            ->image()
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('set')
                                    ->disabled(),
                                Forms\Components\TextInput::make('edition')
                                    ->disabled(),
                                Forms\Components\TextInput::make('language')
                                    ->disabled(),
                                Forms\Components\TextInput::make('condition')
                                    ->disabled(),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->disabled(),
                                Forms\Components\TextInput::make('price_per_unit')
                                    ->disabled(),
                                Forms\Components\TextInput::make('currency')
                                    ->disabled(),
                            ]),
                        Forms\Components\Toggle::make('tradeable')
                            ->label('Available for Trade')
                            ->disabled(),
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
                    ->defaultImageUrl(url('/images/placeholder-card.svg')),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Seller')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_critical')
                    ->label('Critical')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('')
                    ->trueColor('danger'),

                Tables\Columns\TextColumn::make('evaluation_flags')
                    ->label('Flags')
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        $flags = is_array($record->evaluation_flags) ? $record->evaluation_flags : [];
                        return count($flags) > 0 ? count($flags) . ' flags' : 'None';
                    })
                    ->color(function ($state, $record) {
                        $flags = is_array($record->evaluation_flags) ? $record->evaluation_flags : [];
                        return count($flags) > 0 ? 'warning' : 'success';
                    })
                    ->tooltip(function ($record) {
                        $flags = is_array($record->evaluation_flags) ? $record->evaluation_flags : [];
                        return count($flags) > 0 ? implode(', ', array_map(fn($f) => str_replace('_', ' ', ucwords($f, '_')), $flags)) : null;
                    }),

                Tables\Columns\TextColumn::make('price_per_unit')
                    ->label('Price')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\Filter::make('critical_only')
                    ->label('Critical Only')
                    ->query(fn (Builder $query) => $query->where('is_critical', true)),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Review: ' . $record->title)
                    ->modalContent(fn ($record) => view('filament.resources.pending-review-card-view', ['record' => $record]))
                    ->modalWidth('4xl')
                    ->slideOver()
                    ->modalFooterActions(fn ($record) => [
                        Tables\Actions\Action::make('approve')
                            ->label('Approve Card')
                            ->color('success')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Approve this card listing?')
                            ->modalDescription('This card will be approved and the user will be notified.')
                            ->action(function ($record) {
                                $record->update([
                                    'status' => PhysicalCard::STATUS_APPROVED,
                                    'approved_by' => auth()->id(),
                                    'approved_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                    'reviewed_at' => now(),
                                    'is_critical' => false,
                                ]);

                                // Send notification to user
                                if (config('mail.enabled')) {
                                    $record->user->notify(new \App\Notifications\CardApproved($record));
                                }

                                Notification::make()
                                    ->title('Card Approved')
                                    ->body('The card has been approved and the user has been notified.')
                                    ->success()
                                    ->send();
                            }),

                        Tables\Actions\Action::make('reject')
                            ->label('Reject Card')
                            ->color('danger')
                            ->icon('heroicon-o-x-circle')
                            ->form([
                                Forms\Components\Textarea::make('rejection_reason')
                                    ->label('Reason for Rejection')
                                    ->required()
                                    ->rows(4)
                                    ->placeholder('Please provide a clear reason for rejection...'),
                                Forms\Components\Textarea::make('review_notes')
                                    ->label('Internal Notes (optional)')
                                    ->rows(3)
                                    ->placeholder('Internal notes for review history...'),
                            ])
                            ->action(function ($record, array $data) {
                                $record->update([
                                    'status' => PhysicalCard::STATUS_REJECTED,
                                    'rejection_reason' => $data['rejection_reason'],
                                    'review_notes' => $data['review_notes'] ?? null,
                                    'reviewed_by' => auth()->id(),
                                    'reviewed_at' => now(),
                                ]);

                                // Send notification to user
                                if (config('mail.enabled')) {
                                    $record->user->notify(new \App\Notifications\CardRejected($record));
                                }

                                Notification::make()
                                    ->title('Card Rejected')
                                    ->body('The card has been rejected and the user has been notified.')
                                    ->success()
                                    ->send();
                            }),
                    ]),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePendingReviewCards::route('/'),
        ];
    }
}
