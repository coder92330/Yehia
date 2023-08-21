<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class AgentChart extends LineChartWidget
{
    protected static ?int $sort = 4;

    protected function getHeading(): ?string
    {
        return __('attributes.new_agents_per_month');
    }

    protected function getData(): array
    {
        $agents = Agent::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->get()
            ->groupBy(fn($agent) => $agent->created_at->format('M'))
            ->map(fn($agent) => $agent->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('attributes.registered_agents'),
                    'data' => $agents,
                ],
            ],
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray(),
        ];
    }
}
