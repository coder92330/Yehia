<?php

namespace App\Agent\Resources\Event\EventResource\Pages;

use App\Agent\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListEvents extends ListRecords
{
    use ContextualPage, ListRecords\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string|array $middlewares = 'permission:List Bookings';

    protected function getTableQuery(): Builder
    {
        return auth('agent')->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })
            ? parent::getTableQuery()->whereRelation('agent.company', 'id', auth('agent')->user()->company_id)->latest()
            : parent::getTableQuery()->whereAgentId(auth('agent')->id())->latest();
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make()
                ->visible(fn() => auth('agent')->user()->hasPermissionTo('Create Bookings', 'agent')),
        ];
    }
}
