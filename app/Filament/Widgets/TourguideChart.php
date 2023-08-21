<?php

namespace App\Filament\Widgets;

use App\Models\Tourguide;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class TourguideChart extends LineChartWidget
{
    protected static ?int $sort = 5;

    protected function getHeading(): ?string
    {
        return __('attributes.new_tourguides_per_month');
    }

    protected function getData(): array
    {
        $tourguides = Tourguide::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->get()
            ->groupBy(fn($tourguide) => $tourguide->created_at->format('M'))
            ->map(fn($tourguide) => $tourguide->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('attributes.registered_tourguides'),
                    'data' => $tourguides,
                ],
            ],
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray()
        ];
    }
}
