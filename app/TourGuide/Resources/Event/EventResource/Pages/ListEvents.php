<?php

namespace App\TourGuide\Resources\Event\EventResource\Pages;

use App\Models\Event;
use App\TourGuide\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListEvents extends ListRecords
{
    use ContextualPage, ListRecords\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereHas('orders', function ($q) {
            $q->whereHas('tourguides', function ($q) {
                $q->where([
                    'status'       => 'approved',
                    'agent_status' => 'approved',
                    'tourguide_id' => auth('tourguide')->id(),
                ]);
            });
        });
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
