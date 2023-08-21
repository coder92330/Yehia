<?php

namespace App\TourGuide\Widgets;

use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class ProfileViewChart extends LineChartWidget
{
    protected static ?string $heading = 'Profile Views';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $views = auth('tourguide')->user()->views()
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->get()
            ->groupBy(fn($view) => $view->created_at->format('M'))
            ->map(fn($view) => $view->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('attributes.profile_views'),
                    'data' => $views,
                ],
            ],
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray(),
        ];
    }
}
