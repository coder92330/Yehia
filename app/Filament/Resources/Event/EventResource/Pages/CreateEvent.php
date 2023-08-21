<?php

namespace App\Filament\Resources\Event\EventResource\Pages;

use App\Filament\Resources\Event\EventResource;
use App\Models\Agent;
use App\Notifications\DatabaseNotification;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string|array $middlewares = ['permission:Create Bookings'];

    protected function afterCreate(): void
    {
        $this->record->agent->notify(new DatabaseNotification(
            __('notifications.booking.created.title'),
            __('notifications.booking.created.body', ['event' => $this->record->name, 'user' => auth()->user()->name]),
            'order',
            $this->record->id,
        ));
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
