<?php

namespace App\Agent\Widgets;

use App\Models\Agent;
use App\Models\Event;
use App\Models\Order;
use App\Models\Tourguide;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class ConfirmedBookingChart extends LineChartWidget
{
    protected static ?int $sort = 4;

    protected function getHeading(): ?string
    {
        return __('widgets.headings.confirmed_bookings_per_month');
    }

    protected function getData(): array
    {
        $orders = auth('agent')->user()->hasRole('admin')
            ? Order::whereRelation('event.company', 'companies.id', auth('agent')->user()->company_id)
            : Order::where(['orderable_type' => Agent::class, 'orderable_id' => auth('agent')->id()]);

        $orders = $orders
            ->get()
            ->groupBy(fn($booking) => $booking->created_at->format('M'))
            ->map(fn($book) => $book->count())
            ->toArray();

        return [
            'labels' => collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(fn($month) => Carbon::createFromDate(null, $month, null)->getTranslatedShortMonthName())->toArray(),
            'datasets' => [
                [
                    'label' => __('attributes.confirmed_bookings'),
                    'data'  => $orders,
                ],
            ],
        ];
    }
}
