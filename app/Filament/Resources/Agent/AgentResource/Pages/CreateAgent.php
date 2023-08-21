<?php

namespace App\Filament\Resources\Agent\AgentResource\Pages;

use App\Filament\Resources\Agent\AgentResource;
use App\Models\Company;
use App\Models\Country;
use App\Models\Style;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAgent extends CreateRecord
{
    protected static string $resource = AgentResource::class;

    protected static ?string $title = 'Create Company Admin';

    protected static string|array $middlewares = 'permission:Create Company Admins';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (is_null($data['email_verified_at']) || $data['email_verified_at'] === '') {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
        }

        $data['country_id'] = Company::find($data['company_id'])->country?->id ?? Country::where('name->en', 'Egypt')->first()->id;

        $data['style_id'] = Style::defaultStyleId();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->company->agents->count() <= 1
            ? $this->record->assignRole(Role::findByName('super_admin', 'agent'))
            : $this->record->assignRole(Role::findByName('admin', 'agent'));
    }
}

