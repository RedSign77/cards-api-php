<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Jobs;

use App\Models\PhysicalCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoEvaluateCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PhysicalCard $physicalCard
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Auto-evaluating physical card", ['card_id' => $this->physicalCard->id]);

        // Perform automatic evaluation checks
        $evaluation = $this->performAutoEvaluation();

        if ($evaluation['passed']) {
            // Auto-approve the card - set approved_by to system user (id: 1) and approved_at
            $this->physicalCard->update([
                'status' => PhysicalCard::STATUS_APPROVED,
                'is_critical' => false,
                'approved_by' => 1, // System user
                'approved_at' => now(),
                'evaluation_flags' => $evaluation['flags'],
            ]);

            Log::info("Physical card auto-approved", [
                'card_id' => $this->physicalCard->id,
                'flags' => $evaluation['flags'],
            ]);
        } else {
            // Move to manual review
            $this->physicalCard->update([
                'status' => PhysicalCard::STATUS_UNDER_REVIEW,
                'is_critical' => $evaluation['is_critical'],
                'evaluation_flags' => $evaluation['flags'],
            ]);

            Log::info("Physical card moved to manual review", [
                'card_id' => $this->physicalCard->id,
                'is_critical' => $evaluation['is_critical'],
                'flags' => $evaluation['flags'],
            ]);
        }
    }

    /**
     * Perform automatic evaluation checks
     *
     * @return array ['passed' => bool, 'is_critical' => bool, 'flags' => array]
     */
    protected function performAutoEvaluation(): array
    {
        $flags = [];
        $isCritical = false;

        // RULE 1: Suspiciously low or high price
        if ($this->physicalCard->price_per_unit !== null) {
            if ($this->physicalCard->price_per_unit < 0.01) {
                $flags[] = 'price_too_low';
                $isCritical = true;
            } elseif ($this->physicalCard->price_per_unit > 50000) {
                $flags[] = 'price_too_high';
                $isCritical = true;
            } elseif ($this->physicalCard->price_per_unit < 0.50) {
                $flags[] = 'price_suspiciously_low';
            } elseif ($this->physicalCard->price_per_unit > 10000) {
                $flags[] = 'price_suspiciously_high';
            }
        }

        // RULE 2: Card in damaged/poor condition without a photo
        if (in_array($this->physicalCard->condition, ['Poor', 'Played']) && empty($this->physicalCard->image)) {
            $flags[] = 'damaged_without_photo';
            $isCritical = true;
        }

        // RULE 3: Prohibited terms in description or title (proxy, fake, counterfeit, reproduction)
        $prohibitedTerms = ['proxy', 'fake', 'counterfeit', 'reproduction', 'replica', 'bootleg', 'unofficial'];
        $searchText = strtolower($this->physicalCard->title . ' ' . $this->physicalCard->description);

        foreach ($prohibitedTerms as $term) {
            if (str_contains($searchText, $term)) {
                $flags[] = 'prohibited_term_' . $term;
                $isCritical = true;
                break;
            }
        }

        // RULE 4: New user (fewer than 5 approved listings)
        $userApprovedListings = PhysicalCard::where('user_id', $this->physicalCard->user_id)
            ->where('id', '!=', $this->physicalCard->id)
            ->where('status', PhysicalCard::STATUS_APPROVED)
            ->count();

        if ($userApprovedListings < 5) {
            $flags[] = 'new_user';
            if ($userApprovedListings === 0) {
                $flags[] = 'first_listing';
            }
        }

        // RULE 5: Missing required fields
        if (empty($this->physicalCard->title)) {
            $flags[] = 'missing_title';
            $isCritical = true;
        }

        if (empty($this->physicalCard->condition)) {
            $flags[] = 'missing_condition';
            $isCritical = true;
        }

        // RULE 6: Invalid quantity
        if ($this->physicalCard->quantity <= 0) {
            $flags[] = 'invalid_quantity';
            $isCritical = true;
        }

        // RULE 7: Large quantity (over 100) - might be bulk seller
        if ($this->physicalCard->quantity > 100) {
            $flags[] = 'large_quantity';
        }

        // RULE 8: No image provided
        if (empty($this->physicalCard->image)) {
            $flags[] = 'no_image';
        }

        // RULE 9: Description contains suspicious patterns (external links, contact info)
        if (!empty($this->physicalCard->description)) {
            $suspiciousPatterns = [
                '/https?:\/\//i' => 'external_link',
                '/\b[\w\.-]+@[\w\.-]+\.\w+\b/' => 'email_address',
                '/\b\d{3}[-.]?\d{3}[-.]?\d{4}\b/' => 'phone_number',
                '/\bwhatsapp\b/i' => 'whatsapp_mention',
                '/\btelegram\b/i' => 'telegram_mention',
            ];

            foreach ($suspiciousPatterns as $pattern => $flag) {
                if (preg_match($pattern, $this->physicalCard->description)) {
                    $flags[] = $flag;
                    $isCritical = true;
                }
            }
        }

        // Determine if evaluation passed (auto-approve)
        // Only approve if NO critical flags and maximum 2 non-critical flags
        $passed = !$isCritical && count($flags) <= 2;

        return [
            'passed' => $passed,
            'is_critical' => $isCritical,
            'flags' => $flags,
        ];
    }
}
