<?php

namespace App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\Pages;

use App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListConfirmedOrder extends ListRecords
{
    use ContextualPage;

    protected static string $resource = ConfirmedOrderResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereHas('event.agent.company', fn($query) => $query->whereId(auth('agent')->user()->company_id))
            ->whereHas('tourguides', fn($q) => $q->where([['status', 'approved'], ['agent_status', 'approved']]))
            ->when(auth('agent')->user()->hasRole('agent'), function ($query) {
                $query->whereHas('orderable', fn($query) => $query->where([
                    ['orderable_id', auth('agent')->id()],
                    ['orderable_type', auth('agent')->user()->getMorphClass()]
                ]));
            });
    }

    protected function getActions(): array
    {
        return [];
    }
}
