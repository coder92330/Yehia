<?php

namespace App\Agent\Resources\Event\EventResource\Pages;

use App\Agent\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ViewEvent extends ViewRecord
{
    use ContextualPage, ViewRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string|array $middlewares = 'permission:View Bookings|Show Bookings';

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make()
                ->visible(fn() => auth('agent')->user()->hasPermissionTo('Edit Bookings')),
        ];
    }
}
