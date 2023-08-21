<?php

namespace App\Filament\Resources\Company\CompanyResource\Pages;

use App\Filament\Resources\Company\CompanyResource;
use App\Models\Agent;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateCompany extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CompanyResource::class;

    protected static string|array $middlewares = ['permission:Create Companies'];

    protected function afterCreate(): void
    {
        if (isset($this->data['agents']) && is_array($this->data['agents']) && count($this->data['agents']) > 0) {
            Agent::whereIn('email', collect($this->data['agents'])->pluck('email'))
                ->get()
                ->each(function ($agent, $key) {
                    $key === 0
                        ? $agent->assignRole(Role::findByName('super_admin', 'agent'))
                        : $agent->assignRole(Role::findByName('admin', 'agent'));
                });
        }
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}

