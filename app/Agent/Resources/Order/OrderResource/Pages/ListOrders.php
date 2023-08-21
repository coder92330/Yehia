<?php

namespace App\Agent\Resources\Order\OrderResource\Pages;

use App\Agent\Resources\Order\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListOrders extends ListRecords
{
    use ContextualPage;

    protected static string $resource = OrderResource::class;

    protected static string|array $middlewares = 'permission:List Confirmed Bookings';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereHas('event.agent.company', fn($query) => $query->whereId(auth('agent')->user()->company_id))
            ->whereHas('tourguides', fn($q) => $q->where('status', '!=', 'approved')->orWhere('agent_status', '!=', 'approved'))
            ->when(auth('agent')->user()->hasRole('agent'), function ($query) {
                $query->whereHas('orderable', fn($query) => $query->where([
                    ['orderable_id', auth('agent')->id()],
                    ['orderable_type', auth('agent')->user()->getMorphClass()]
                ]));
            });
    }

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make()->visible(fn() => auth('agent')->user()->hasPermissionTo('Create Confirmed Bookings')),
        ];
    }
}
