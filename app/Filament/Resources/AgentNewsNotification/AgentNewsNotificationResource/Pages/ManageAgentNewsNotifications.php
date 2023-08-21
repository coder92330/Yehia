<?php

namespace App\Filament\Resources\AgentNewsNotification\AgentNewsNotificationResource\Pages;

use App\Facades\Firebase;
use App\Filament\Resources\AgentNewsNotification\AgentNewsNotificationResource;
use App\Models\Agent;
use App\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAgentNewsNotifications extends ManageRecords
{
    protected static string $resource = AgentNewsNotificationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(__('notifications.sent.successfully'))->successNotificationTitle(__('notifications.sent.successfully'))
                ->mutateFormDataUsing(fn(array $data): array => array_merge($data, ['user_id' => auth()->id()]))
                ->after(function ($record) {
                    Agent::chunk(500, function ($agents) use ($record) {
                        $agents->each(function ($agent) use ($record) {
                            $agent->notify(new DatabaseNotification(
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
