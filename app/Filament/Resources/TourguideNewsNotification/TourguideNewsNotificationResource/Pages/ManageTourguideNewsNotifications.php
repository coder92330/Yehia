<?php

namespace App\Filament\Resources\TourguideNewsNotification\TourguideNewsNotificationResource\Pages;

use App\Facades\Firebase;
use App\Filament\Resources\TourguideNewsNotification\TourguideNewsNotificationResource;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTourguideNewsNotifications extends ManageRecords
{
    protected static string $resource = TourguideNewsNotificationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(__('notifications.sent.successfully'))
                ->mutateFormDataUsing(fn(array $data): array => array_merge($data, ['user_id' => auth()->id()]))
                ->after(function ($record) {
                    Tourguide::chunk(500, function ($tourguides) use ($record) {
                        $tourguides->each(function ($tourguide) use ($record) {
                            $tourguide->notify(new DatabaseNotification(
                                $record->title,
                                $record->body,
                                'news',
                                ['id' => $record->id]
                            ));
                        });
                    });
                }),
        ];
    }
}
