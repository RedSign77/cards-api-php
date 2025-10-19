<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Observers;

use App\Jobs\AutoEvaluateCard;
use App\Models\CardStatusHistory;
use App\Models\PhysicalCard;

class PhysicalCardObserver
{
    /**
     * Handle the PhysicalCard "updating" event.
     */
    public function updating(PhysicalCard $physicalCard): void
    {
        // If a rejected card is being edited (any field except status), re-submit for review
        if ($physicalCard->getOriginal('status') === PhysicalCard::STATUS_REJECTED &&
            $physicalCard->isDirty() &&
            !$physicalCard->isDirty('status')) {

            // Reset to pending_auto for re-evaluation
            $physicalCard->status = PhysicalCard::STATUS_PENDING_AUTO;
            $physicalCard->rejection_reason = null;
            $physicalCard->review_notes = null;
            $physicalCard->reviewed_by = null;
            $physicalCard->reviewed_at = null;
            $physicalCard->approved_by = null;
            $physicalCard->approved_at = null;
            $physicalCard->is_critical = false;
            $physicalCard->evaluation_flags = null;

            // Queue auto-evaluation job
            AutoEvaluateCard::dispatch($physicalCard);
        }

        // Check if status is being changed
        if ($physicalCard->isDirty('status')) {
            $oldStatus = $physicalCard->getOriginal('status');
            $newStatus = $physicalCard->status;

            // Determine action type based on who made the change
            $actionType = 'user_edit';
            $userId = auth()->id();
            $notes = null;
            $metadata = [];

            // If status changed from rejected to pending_auto, it's a re-submission
            if ($oldStatus === PhysicalCard::STATUS_REJECTED && $newStatus === PhysicalCard::STATUS_PENDING_AUTO) {
                $actionType = 'user_resubmission';
                $userId = auth()->id() ?? $physicalCard->user_id;
                $notes = 'Card edited and re-submitted for review';
            }
            // If approved_by changed to system (ID 1), it's auto-evaluation
            elseif ($physicalCard->isDirty('approved_by') && $physicalCard->approved_by === 1) {
                $actionType = 'auto_evaluation';
                $userId = 1;
                $metadata['evaluation_flags'] = $physicalCard->evaluation_flags;
                $metadata['is_critical'] = $physicalCard->is_critical;
            }
            // If reviewed_by is set and status is approved, it's supervisor approval
            elseif ($physicalCard->isDirty('reviewed_by') && $newStatus === PhysicalCard::STATUS_APPROVED) {
                $actionType = 'supervisor_approval';
                $userId = $physicalCard->reviewed_by;
                $metadata['approved_by'] = $physicalCard->approved_by;
            }
            // If reviewed_by is set and status is rejected, it's supervisor rejection
            elseif ($physicalCard->isDirty('reviewed_by') && $newStatus === PhysicalCard::STATUS_REJECTED) {
                $actionType = 'supervisor_rejection';
                $userId = $physicalCard->reviewed_by;
                $notes = $physicalCard->rejection_reason;
                $metadata['review_notes'] = $physicalCard->review_notes;
            }

            // Log the status change
            CardStatusHistory::create([
                'physical_card_id' => $physicalCard->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'action_type' => $actionType,
                'user_id' => $userId,
                'notes' => $notes,
                'metadata' => $metadata,
            ]);
        }
    }

    /**
     * Handle the PhysicalCard "created" event.
     */
    public function created(PhysicalCard $physicalCard): void
    {
        // Log initial creation
        CardStatusHistory::create([
            'physical_card_id' => $physicalCard->id,
            'old_status' => null,
            'new_status' => $physicalCard->status,
            'action_type' => 'card_created',
            'user_id' => $physicalCard->user_id,
            'notes' => 'Card listing created',
            'metadata' => [
                'title' => $physicalCard->title,
                'price' => $physicalCard->price_per_unit,
                'currency' => $physicalCard->currency,
            ],
        ]);
    }
}
