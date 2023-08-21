<?php

namespace App\Filament\Resources\Order\OrderResource\Pages;

use App\Filament\Resources\Order\OrderResource;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = ['permission:Create Confirmed Bookings'];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['orderable_id']   = auth()->user()->id;
        $data['orderable_type'] = User::class;
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->tourguide->notify(new DatabaseNotification(
            __('notifications.booking.created.title'),
            __('notifications.booking.created.body', ['event' => $this->record->event->name, 'user' => auth()->user()->name]),
            'order',
            ['id' => $this->record->id],
        ));
    }

    protected function getActions(): array
    {
        return [

        ];
    }
}
