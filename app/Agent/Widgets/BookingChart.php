<?php

namespace App\Agent\Widgets;

use App\Models\Event;
use App\Models\Tourguide;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class BookingChart extends LineChartWidget
{
    protected static ?int $sort = 3;

    protected function getHeading(): ?string
    {
        return __('widgets.headings.bookings_per_month');
    }

    protected function getData(): array
    {
        $months = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray();

        $bookings = auth('agent')->user()->hasRole('admin')
            ? Event::whereRelation('company', 'companies.id', auth('agent')->user()->company_id)
            : Event::where('agent_id', auth('agent')->id());

        $bookings = $bookings
            ->get()
            ->groupBy(fn($booking) => $booking->created_at->format('M'))
            ->map(fn($book) => $book->count())
            ->toArray();

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => __('attributes.bookings'),
                    'data'  => $bookings,
                ],
            ],
        ];
    }
}
