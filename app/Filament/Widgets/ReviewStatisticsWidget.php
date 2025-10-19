<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Models\CardStatusHistory;
use App\Models\PhysicalCard;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStatisticsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->isSupervisor() ?? false;
    }

    protected function getStats(): array
    {
        // Last 30 days statistics
        $startDate = now()->subDays(30);

        // Total approvals
        $totalApprovals = CardStatusHistory::where('action_type', 'supervisor_approval')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Total rejections
        $totalRejections = CardStatusHistory::where('action_type', 'supervisor_rejection')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Total reviews (approvals + rejections)
        $totalReviews = $totalApprovals + $totalRejections;

        // Rejection rate percentage
        $rejectionRate = $totalReviews > 0 ? round(($totalRejections / $totalReviews) * 100, 1) : 0;

        // Auto-approvals
        $autoApprovals = CardStatusHistory::where('action_type', 'auto_evaluation')
            ->where('new_status', PhysicalCard::STATUS_APPROVED)
            ->where('created_at', '>=', $startDate)
            ->count();

        // Average review time for completed reviews
        $avgReviewTime = $this->calculateAverageReviewTime();

        // Total cards in system
        $totalCards = PhysicalCard::count();

        return [
            Stat::make('Rejection Rate (30d)', $rejectionRate . '%')
                ->description("{$totalRejections} of {$totalReviews} reviews rejected")
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($this->getRejectionRateColor($rejectionRate))
                ->chart($this->getRejectionTrend()),

            Stat::make('Auto-Approved (30d)', $autoApprovals)
                ->description('System auto-approved cards')
                ->descriptionIcon('heroicon-o-bolt')
                ->color('info')
                ->url(route('filament.admin.resources.card-audit-logs.index')),

            Stat::make('Avg. Review Time', $avgReviewTime)
                ->description('Time from submission to decision')
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary'),

            Stat::make('Total Cards', $totalCards)
                ->description('All physical card listings')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('gray')
                ->url(route('filament.admin.resources.physical-cards.index')),
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

    protected function getRejectionTrend(): array
    {
        // Last 7 days rejection rate trend
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $endDate = $date->copy()->endOfDay();

            $approvals = CardStatusHistory::where('action_type', 'supervisor_approval')
                ->whereBetween('created_at', [$date, $endDate])
                ->count();

            $rejections = CardStatusHistory::where('action_type', 'supervisor_rejection')
                ->whereBetween('created_at', [$date, $endDate])
                ->count();

            $total = $approvals + $rejections;
            $rate = $total > 0 ? round(($rejections / $total) * 100) : 0;

            $trend[] = $rate;
        }
        return $trend;
    }

    protected function calculateAverageReviewTime(): string
    {
        $reviews = CardStatusHistory::whereIn('action_type', ['supervisor_approval', 'supervisor_rejection'])
            ->where('created_at', '>=', now()->subDays(30))
            ->with('physicalCard')
            ->get();

        if ($reviews->isEmpty()) {
            return 'N/A';
        }

        $totalMinutes = 0;
        $count = 0;

        foreach ($reviews as $review) {
            if ($review->physicalCard) {
                $minutes = $review->physicalCard->created_at->diffInMinutes($review->created_at);
                $totalMinutes += $minutes;
                $count++;
            }
        }

        if ($count === 0) {
            return 'N/A';
        }

        $avgMinutes = round($totalMinutes / $count);

        // Format as hours if > 60 minutes
        if ($avgMinutes >= 60) {
            $hours = round($avgMinutes / 60, 1);
            return $hours . 'h';
        }

        return $avgMinutes . 'm';
    }
}
