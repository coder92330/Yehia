<?php

namespace App\TourGuide\Widgets;

use App\Models\Appointment;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Saade\FilamentFullCalendar\Widgets\Concerns\CantManageEvents;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    use CantManageEvents;

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(?array $event = null): bool
    {
        return false;
    }

    public function getCreateEventModalTitle(): string
    {
        return __('filament::resources/pages/create-record.title', ['label' => __('attributes.free_time')]);
    }

    public function getEditEventModalTitle(): string
    {
        return __('filament::resources/pages/edit-record.title', ['label' => __('attributes.free_time')]);
    }

    public function onCreateEventClick(array $date): void
    {
        $filter = Carbon::parse($date['start'] ?? $date['date'])->addDay()->toDateString();
        $appointment = Appointment::whereTourguideId(auth('tourguide')->id())
            ->whereDate('start_at', $filter)
            ->whereDate('end_at', '>=', $filter)
            ->exists();
        if ($appointment) {
            Notification::make()->danger()->title(__('notifications.appointment_already_exists'))->send();
        } else {
            parent::onCreateEventClick($date);
        }
    }

    protected static function getCreateEventFormSchema(): array
    {
        return [
            DatePicker::make('start')
                ->required(),
            DatePicker::make('end')
                ->default(null),
        ];
    }

    public function onEventClick($event): void
    {
        if (method_exists($this, 'resolveEventRecord')) {
            $this->event = $this->resolveEventRecord($event);
        } else {
            $this->event_id = $event['id'] ?? null;
        }

        $this->dispatchBrowserEvent('open-modal', ['id' => 'fullcalendar--delete-event-modal']);
    }

    public function onDeleteEventSubmit(): void
    {
        try {
            DB::beginTransaction();
            $this->dispatchBrowserEvent('close-modal', ['id' => 'fullcalendar--delete-event-modal']);
            auth('tourguide')->user()->appointments()->whereId($this->event_id)->delete();
            Notification::make()->success()->title(__('notifications.deleted_successfully', ['field' => 'Appointment']))->send();
            $this->refreshEvents();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }

    public function createEvent(array $data): void
    {
        try {
            DB::beginTransaction();
            Carbon::parse($data['start'])->daysUntil(Carbon::parse($data['end']))->forEach(function ($date) {
                auth('tourguide')->user()->appointments()->create([
                    'start_at' => $date->toDateString(),
                    'end_at' => $date->addDay()->toDateString(),
                ]);
            });
            Notification::make()->success()->title(__('notifications.created_successfully', ['field' => 'Appointment']))->send();
            $this->refreshEvents();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }

    protected function getViewData(): array
    {
        $orders = [];
        $tourguideOrders = auth('tourguide')->user()->orders()->where(['status' => 'approved', 'agent_status' => 'approved'])->get();
        foreach ($tourguideOrders as $order) {
            if (isset($order->event)) {
                $orders[] = [
                    'id' => $order->id,
                    'title' => $order->event->name ?? __('messages.aviailable_for_booking'),
                    'start' => $order->event->start_at ?? $order->start_at,
                    'end' => $order->event->end_at ?? $order->end_at,
                    'url' => route('tour-guide.resources.confirmed-bookings.view', $order->id),
                    'shouldOpenInNewTab' => true,
                ];
            } else {
                $orders[] = [
                    'id' => $order->id,
                    'title' => __('messages.aviailable_for_booking'),
                    'start' => $order->start_at,
                    'end' => $order->end_at,
                ];
            }
        }
        return $orders;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return auth('tourguide')->user()->appointments->toArray();
    }
}
