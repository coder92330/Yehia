<?php

namespace App\Agent\Widgets;

use App\Models\Event;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Saade\FilamentFullCalendar\Widgets\Concerns\CanManageEvents;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(?array $event = null): bool
    {
        return false;
    }

    public function getAllEvents(): array
    {
        $events = [];
        foreach (Event::whereRelation('agent.company', 'id', auth('agent')->user()->company->id)->get() as $event) {
            $events[] = [
                'id'                 => $event->id,
                'title'              => $event->name,
                'start'              => $event->start_at->toDateTimeString(),
                'end'                => $event->end_at->toDateTimeString(),
                'url'                => route('agent.resources.bookings.edit', $event->id),
                'shouldOpenInNewTab' => true,
            ];
        }
        return $events;
    }

    public function onEventDrop($newEvent, $oldEvent, $relatedEvents): void
    {
        try {
            DB::beginTransaction();
            Event::where('id', $oldEvent['id'])->update([
                'start_at' => $newEvent['start'],
                'end_at' => $newEvent['end'],
            ]);
            DB::commit();
            Notification::make()->success()->title(__('notifications.update_successfully', ['field' => __('attributes.event')]))->send();
            $this->refreshEvents();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->danger()->title(__('notifications.update_failed', ['field' => __('attributes.event')]))->send();
        }
    }

    protected function getViewData(): array
    {
        return $this->getAllEvents();
    }
}
