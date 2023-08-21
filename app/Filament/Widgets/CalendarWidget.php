<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    protected function getViewData(): array
    {
        return $this->getAllEvents();
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return $this->getAllEvents();
    }

    public function getAllEvents(): array
    {
        $events = [];
        foreach (Event::all() as $event) {
            $events[] = [
                'title'              => $event->name,
                'start'              => $event->start_at,
                'end'                => $event->end_at,
                'url'                => route('filament.resources.bookings.edit', $event->id),
                'shouldOpenInNewTab' => true,
            ];
        }
        return $events;
    }
}
