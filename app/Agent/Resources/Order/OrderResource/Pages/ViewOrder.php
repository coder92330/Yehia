<?php

namespace App\Agent\Resources\Order\OrderResource\Pages;

use App\Agent\Resources\Order\OrderResource;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ViewOrder extends ViewRecord
{
    use ContextualPage;

    protected static string $resource = OrderResource::class;

    protected static ?string $title = 'Order';

    protected static string $view = 'filament.resources.order.pages.view-order';

    protected static string|array $middlewares = 'permission:View Confirmed Bookings';

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public function changeOrderStatus($tourguide_id, $status)
    {
        $this->record->tourguides()->updateExistingPivot($tourguide_id, ['agent_status' => $status]);
        $this->record->refresh();
        $status === 'approved'
            ? Tourguide::find($tourguide_id)->notify(new DatabaseNotification(
                __('notifications.booking.approved.title'),
                __('notifications.booking.approved.body', ['event' => $this->record->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]),
                'order',
                ['id' => $this->record->id],
            ))
            : Tourguide::find($tourguide_id)->notify(new DatabaseNotification(
                __('notifications.booking.rejected.title'),
                __('notifications.booking.rejected.body', ['event' => $this->record->event->name, 'user' => ucwords(auth('agent')->user()->full_name)]),
                'order',
                ['id' => $this->record->id],
            ));
        $status === 'approved'
            ? Notification::make()->success()->title("Order $status")->send()
            : Notification::make()->danger()->title("Order $status")->send();
    }

    protected function getActions(): array
    {
        return [
            EditAction::make()->visible(fn() => auth('agent')->user()->hasPermissionTo('Edit Confirmed Bookings')),
        ];
    }

    public function unassignTourguide($id)
    {
        try {
            $this->record->tourguides()->detach($id);
            $this->record->refresh();
            Notification::make()->success()->title(__('notifications.tourguide_unassigned'))->send();
        } catch (\Exception $e) {
            Notification::make()->danger()->title(__('messages.something_went_wrong'))->send();
        }
    }
}
