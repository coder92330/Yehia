<?php

namespace App\Filament\Resources\Agent\AgentResource\Pages;

use App\Filament\Resources\Agent\AgentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAgents extends ListRecords
{
    protected static string $resource = AgentResource::class;

    protected static ?string $title = 'Company Admins';

    protected static string|array $middlewares = 'permission:List Company Admins';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereHas('roles', function ($query) {
            $query->where([['name', 'super_admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'admin'], ['guard_name', 'agent']]);
        });
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
