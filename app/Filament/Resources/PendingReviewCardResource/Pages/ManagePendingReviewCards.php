<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\PendingReviewCardResource\Pages;

use App\Filament\Resources\PendingReviewCardResource;
use App\Models\PhysicalCard;
use App\Notifications\CardApproved;
use App\Notifications\CardRejected;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManagePendingReviewCards extends ManageRecords
{
    protected static string $resource = PendingReviewCardResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->modalHeading(fn ($record) => $record->title)
                ->modalContent(fn ($record) => view('filament.resources.pending-review-card-view', ['record' => $record]))
                ->modalWidth('4xl')
                ->slideOver()
                ->modalFooterActions(fn ($record) => [
                    Actions\Action::make('approve')
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
                                $record->user->notify(new CardApproved($record));
                            }

                            Notification::make()
                                ->title('Card Approved')
                                ->body('The card has been approved and the user has been notified.')
                                ->success()
                                ->send();

                            return redirect()->to(PendingReviewCardResource::getUrl('index'));
                        }),

                    Actions\Action::make('reject')
                        ->label('Reject Card')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
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
                        ->modalHeading('Reject this card listing?')
                        ->modalDescription('Please provide a reason for rejecting this card.')
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
                                $record->user->notify(new CardRejected($record));
                            }

                            Notification::make()
                                ->title('Card Rejected')
                                ->body('The card has been rejected and the user has been notified.')
                                ->success()
                                ->send();

                            return redirect()->to(PendingReviewCardResource::getUrl('index'));
                        }),
                ]),
        ];
    }
}
