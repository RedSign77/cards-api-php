<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Models\CardStatusHistory;
use App\Models\PhysicalCard;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupervisorReviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    protected function getStats(): array
    {
        // 1. Pending Reviews (Under Review status)
        $pendingCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)->count();
        $criticalCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('is_critical', true)
            ->count();

        // 2. Escalated cards (48+ hours)
        $escalatedCount = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('created_at', '<=', now()->subHours(48))
            ->count();

        // 3. Recent approvals (last 30 days)
        $recentApprovals = CardStatusHistory::whereIn('action_type', ['supervisor_approval', 'auto_evaluation'])
            ->where('new_status', PhysicalCard::STATUS_APPROVED)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // 4. Recent rejections (last 30 days)
        $recentRejections = CardStatusHistory::where('action_type', 'supervisor_rejection')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // 5. Rejection rate
        $totalReviews = $recentApprovals + $recentRejections;
        $rejectionRate = $totalReviews > 0 ? round(($recentRejections / $totalReviews) * 100, 1) : 0;

        return [
            Stat::make('Pending Reviews', $pendingCount)
                ->description($criticalCount > 0 ? "{$criticalCount} critical" : 'No critical cards')
                ->descriptionIcon($criticalCount > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.pending-review-cards.index')),

            Stat::make('Escalated (48h+)', $escalatedCount)
                ->description($escalatedCount > 0 ? 'Urgent attention!' : 'All up to date')
                ->descriptionIcon($escalatedCount > 0 ? 'heroicon-o-fire' : 'heroicon-o-check')
                ->color($escalatedCount > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.pending-review-cards.index')),

            Stat::make('Approved (30d)', $recentApprovals)
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.card-audit-logs.index')),

            Stat::make('Rejected (30d)', $recentRejections)
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.card-audit-logs.index')),

            Stat::make('Rejection Rate', $rejectionRate . '%')
                ->description("Based on {$totalReviews} reviews")
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($this->getRejectionRateColor($rejectionRate))
                ->url(route('filament.admin.resources.card-audit-logs.index')),
        ];
    }

    protected function getRejectionRateColor(float $rate): string
    {
        if ($rate >= 50) {
            return 'danger';
        } elseif ($rate >= 30) {
            return 'warning';
        } elseif ($rate >= 10) {
            return 'primary';
        }
        return 'success';
    }
}
