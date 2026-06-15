<?php

namespace App\Filament\Widgets;

use App\Models\Verification;
use App\Models\VerificationReview;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class VerificationStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pendentes', Verification::query()
                ->where('status', Verification::STATUS_UNDER_REVIEW)
                ->count())
                ->description('Aguardando analise')
                ->color('warning'),
            Stat::make('Aprovadas hoje', Verification::query()
                ->where('status', Verification::STATUS_APPROVED)
                ->whereDate('approved_at', Carbon::today())
                ->count())
                ->color('success'),
            Stat::make('Reprovadas hoje', VerificationReview::query()
                ->where('decision', VerificationReview::DECISION_REJECTED)
                ->whereDate('decided_at', Carbon::today())
                ->count())
                ->color('danger'),
            Stat::make('Tempo medio', $this->averageAnalysisTime())
                ->description('Submissao ate aprovacao')
                ->color('info'),
        ];
    }

    private function averageAnalysisTime(): string
    {
        $approvedVerifications = Verification::query()
            ->whereNotNull('submitted_at')
            ->whereNotNull('approved_at')
            ->get(['submitted_at', 'approved_at']);

        if ($approvedVerifications->isEmpty()) {
            return '0 h';
        }

        $averageMinutes = $approvedVerifications
            ->avg(fn (Verification $verification): float => $verification->submitted_at->diffInMinutes($verification->approved_at));

        if ($averageMinutes < 60) {
            return round($averageMinutes).' min';
        }

        return round($averageMinutes / 60, 1).' h';
    }
}
