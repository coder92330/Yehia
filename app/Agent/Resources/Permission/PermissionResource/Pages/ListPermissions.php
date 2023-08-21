<?php

namespace App\Agent\Resources\Permission\PermissionResource\Pages;

use App\Agent\Resources\Permission\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListPermissions extends ListRecords
{
    use ContextualPage;

    protected static string $resource = PermissionResource::class;

    protected function getTableQuery(): Builder
    {
        return auth('agent')->user()->permissions()->getQuery();
    }

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
