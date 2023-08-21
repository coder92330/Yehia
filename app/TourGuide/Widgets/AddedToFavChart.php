<?php

namespace App\TourGuide\Widgets;

use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class AddedToFavChart extends LineChartWidget
{
    protected static ?string $heading = 'Added to Favourites';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $favs = auth('tourguide')->user()->favourites()
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->get()
            ->groupBy(fn($fav) => $fav->created_at->format('M'))
            ->map(fn($fav) => $fav->count())
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('attributes.added_to_fav'),
                    'data' => $favs,
                ],
            ],
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray(),
        ];
    }
}
