<?php

namespace App\Agent\Resources\Agent\AgentResource\Pages;

use App\Agent\Resources\Agent\AgentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListAgents extends ListRecords
{
    use ContextualPage;

    protected static string $resource = AgentResource::class;

    protected static string|array $middlewares = 'permission:List Agents';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->staffs()
            ->where([['id', '!=', auth('agent')->id()], ['company_id', auth('agent')->user()->company_id]]);
    }

    protected function getActions(): array
    {
        $actions = [];
        if (auth('agent')->user()->package->users_limit > auth('agent')->user()->company->agents()->staffs()->count()) {
            $actions[] = Actions\CreateAction::make();
        }

        return $actions;
    }
}
