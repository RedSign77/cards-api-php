<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Models\PhysicalCard;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingReviewsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    protected function getStats(): array
    {
        // Pending reviews count
        $pendingCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)->count();

        // Critical cards (high priority)
        $criticalCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('is_critical', true)
            ->count();

        // Cards waiting > 24 hours
        $waitingLongCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('created_at', '<=', now()->subHours(24))
            ->count();

        // Cards waiting > 48 hours (escalated)
        $escalatedCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('created_at', '<=', now()->subHours(48))
            ->count();

        return [
            Stat::make('Pending Reviews', $pendingCount)
                ->description($criticalCount > 0 ? "{$criticalCount} critical cards" : 'All cards reviewed')
                ->descriptionIcon($criticalCount > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle')
                ->color($criticalCount > 0 ? 'warning' : 'success')
                ->chart($this->getPendingTrend())
                ->url(route('filament.admin.resources.pending-review-cards.index')),

            Stat::make('Waiting 24+ Hours', $waitingLongCount)
                ->description($waitingLongCount > 0 ? 'Needs attention' : 'All up to date')
                ->descriptionIcon($waitingLongCount > 0 ? 'heroicon-o-clock' : 'heroicon-o-check')
                ->color($waitingLongCount > 0 ? 'warning' : 'gray')
                ->url(route('filament.admin.resources.pending-review-cards.index')),

            Stat::make('Escalated (48+ Hours)', $escalatedCount)
                ->description($escalatedCount > 0 ? 'Urgent action required!' : 'No escalations')
                ->descriptionIcon($escalatedCount > 0 ? 'heroicon-o-fire' : 'heroicon-o-check')
                ->color($escalatedCount > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.pending-review-cards.index')),
        ];
    }

    protected function getPendingTrend(): array
    {
        // Last 7 days trend
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
                ->whereDate('created_at', $date)
                ->count();
            $trend[] = $count;
        }
        return $trend;
    }
}
