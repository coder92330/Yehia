<?php

namespace App\Filament\Resources\Admin\AdminResource\Pages;

use App\Filament\Resources\Admin\AdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected static string|array $middlewares = 'permission:List Admins';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('id', '!=', auth()->id());
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
